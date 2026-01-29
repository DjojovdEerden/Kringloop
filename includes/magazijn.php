<?php
require_once __DIR__ . '/db.php';

$voorraadType = $voorraadType ?? ($_GET['type'] ?? 'magazijn');
$voorraadType = $voorraadType === 'winkel' ? 'winkel' : 'magazijn';
$statusIdForType = $voorraadType === 'winkel' ? 1 : 2;
$voorraadLabel = $voorraadType === 'winkel' ? 'Winkelvoorraad' : 'Magazijnvoorraad';

$action = $_GET['action'] ?? 'list';
$errors = [];
$editItem = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
	$artikelId = (int)($_POST['artikel_id'] ?? 0);
	$locatie = trim($_POST['locatie'] ?? '');
	$aantal = (int)($_POST['aantal'] ?? 0);
	$ingeboektOp = trim($_POST['ingeboekt_op'] ?? '');

	if ($artikelId <= 0) {
		$errors[] = 'Selecteer een artikel.';
	}
	if ($locatie === '') {
		$errors[] = 'Locatie is verplicht.';
	}
	if ($aantal < 0) {
		$errors[] = 'Aantal moet 0 of hoger zijn.';
	}
	if ($ingeboektOp === '') {
		$errors[] = 'Ingeboekt op is verplicht.';
	}

	if (empty($errors)) {
		if ($id) {
			$stmt = $pdo->prepare('UPDATE voorraad SET artikel_id = ?, locatie = ?, aantal = ?, status_id = ?, ingeboekt_op = ? WHERE id = ? AND status_id = ?');
			$stmt->execute([$artikelId, $locatie, $aantal, $statusIdForType, $ingeboektOp, $id, $statusIdForType]);
		} else {
			$stmt = $pdo->prepare('INSERT INTO voorraad (artikel_id, locatie, aantal, status_id, ingeboekt_op) VALUES (?, ?, ?, ?, ?)');
			$stmt->execute([$artikelId, $locatie, $aantal, $statusIdForType, $ingeboektOp]);
		}

		header('Location: /Kringloop/voorraad.php?type=' . $voorraadType);
		exit;
	}
}

if ($action === 'delete' && isset($_GET['id'])) {
	$id = (int)$_GET['id'];
	$stmt = $pdo->prepare('DELETE FROM voorraad WHERE id = ? AND status_id = ?');
	$stmt->execute([$id, $statusIdForType]);

	header('Location: /Kringloop/voorraad.php?type=' . $voorraadType);
	exit;
}


if ($action === 'edit' && isset($_GET['id'])) {
	$id = (int)$_GET['id'];
	$stmt = $pdo->prepare('SELECT * FROM voorraad WHERE id = ? AND status_id = ?');
	$stmt->execute([$id, $statusIdForType]);
	$editItem = $stmt->fetch(PDO::FETCH_ASSOC);
	if (!$editItem) {
		header('Location: /Kringloop/voorraad.php?type=' . $voorraadType);
		exit;
	}
}

$artikelen = $pdo->query('SELECT id, naam FROM artikel ORDER BY naam')->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query('
	SELECT v.id, a.naam AS omschrijving, v.aantal
	FROM voorraad v
	JOIN artikel a ON v.artikel_id = a.id
	WHERE v.status_id = ' . (int)$statusIdForType . '
	ORDER BY v.id
');
$voorraadItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $voorraadLabel;
include __DIR__ . '/header.php';
?>

<div class="container my-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h2 class="mb-0"><?php echo htmlspecialchars($voorraadLabel, ENT_QUOTES, 'UTF-8'); ?></h2>
		<a class="btn btn-primary" href="/Kringloop/voorraad.php?type=<?php echo $voorraadType; ?>&action=new">Nieuw Item</a>
	</div>

	<?php if (!empty($errors)): ?>
		<div class="alert alert-danger">
			<ul class="mb-0">
				<?php foreach ($errors as $error): ?>
					<li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ($action === 'new' || $action === 'edit'): ?>
		<?php
			$formData = [
				'id' => $editItem['id'] ?? '',
				'artikel_id' => $editItem['artikel_id'] ?? '',
				'locatie' => $editItem['locatie'] ?? '',
				'aantal' => $editItem['aantal'] ?? 0,
				'status_id' => $editItem['status_id'] ?? '',
				'ingeboekt_op' => $editItem['ingeboekt_op'] ?? date('Y-m-d H:i:s'),
			];
		?>
		<div class="card mb-4">
			<div class="card-body">
				<h5 class="card-title mb-3">
					<?php echo $action === 'edit' ? 'Item bewerken' : 'Nieuw item toevoegen'; ?>
				</h5>
				<form method="post" action="/Kringloop/voorraad.php?type=<?php echo $voorraadType; ?>&action=<?php echo htmlspecialchars($action, ENT_QUOTES, 'UTF-8'); ?>">
					<input type="hidden" name="id" value="<?php echo htmlspecialchars($formData['id'], ENT_QUOTES, 'UTF-8'); ?>">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Artikel</label>
							<select name="artikel_id" class="form-select" required>
								<option value="">Selecteer artikel</option>
								<?php foreach ($artikelen as $artikel): ?>
									<option value="<?php echo $artikel['id']; ?>" <?php echo (string)$artikel['id'] === (string)$formData['artikel_id'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($artikel['naam'], ENT_QUOTES, 'UTF-8'); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Locatie</label>
							<input type="text" name="locatie" class="form-control" value="<?php echo htmlspecialchars($formData['locatie'], ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>
						<div class="col-md-4">
							<label class="form-label">Aantal</label>
							<input type="number" name="aantal" class="form-control" min="0" value="<?php echo htmlspecialchars($formData['aantal'], ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>
						<div class="col-md-4">
							<label class="form-label">Ingeboekt op</label>
							<input type="text" name="ingeboekt_op" class="form-control" value="<?php echo htmlspecialchars($formData['ingeboekt_op'], ENT_QUOTES, 'UTF-8'); ?>" required>
							<div class="form-text">Formaat: YYYY-MM-DD HH:MM:SS</div>
						</div>
					</div>
					<div class="mt-3 d-flex gap-2">
						<button type="submit" class="btn btn-success">Opslaan</button>
						<a class="btn btn-outline-secondary" href="/Kringloop/voorraad.php?type=<?php echo $voorraadType; ?>">Annuleren</a>
					</div>
				</form>
			</div>
		</div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th>ID</th>
						<th>Omschrijving</th>
						<th>Hoeveelheid</th>
						<th>Acties</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($voorraadItems)): ?>
						<tr>
							<td colspan="4" class="text-center">Geen magazijnitems gevonden.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($voorraadItems as $item): ?>
							<tr>
								<td><?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($item['omschrijving'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($item['aantal'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td>
									<a class="btn btn-sm btn-outline-primary" href="/Kringloop/voorraad.php?type=<?php echo $voorraadType; ?>&action=edit&id=<?php echo $item['id']; ?>">Bewerken</a>
									<a class="btn btn-sm btn-outline-danger" href="/Kringloop/voorraad.php?type=<?php echo $voorraadType; ?>&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?');">Verwijderen</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
