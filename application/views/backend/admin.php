<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
<!-- DataTales Example -->
<div class="card border-left-success shadow mb-4">
	<div class="card-header py-3">
		<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
			Add Admin
		</button>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>#</th>
						<th>Username</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1;
					foreach ($admin as $data) : ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $data->username; ?></td>
							<td><?= $data->nama; ?></td>
							<td>
								<a href="#" class="badge badge-warning" data-toggle="modal" data-target="#modalEdit<?= $data->id; ?>">Edit</a> |
								<?php if ($data->id != $this->dt_admin->id) : ?>
									<a href="<?= base_url('dashboard/hapusAdmin/' . $data->id); ?>" class="badge badge-danger tombol-hapus">Hapus</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- modal add data -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Admin</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url('dashboard/addAdmin'); ?>" method="post">
				<div class="modal-body">
					<div class="form-group">
						<label>Username</label>
						<input type="text" class="form-control" name="username" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" class="form-control" name="nama" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="password" autocomplete="off" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Tambah</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php foreach ($admin as $dt) : ?>
	<div class="modal fade" id="modalEdit<?= $dt->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Admin</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="<?= base_url('dashboard/editAdmin'); ?>" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label>Username</label>
							<input type="hidden" name="id" value="<?= $dt->id; ?>">
							<input type="text" class="form-control" name="username" value="<?= $dt->username; ?>" autocomplete="off" required>
						</div>
						<div class="form-group">
							<label>Nama</label>
							<input type="text" class="form-control" name="nama" value="<?= $dt->nama; ?>" autocomplete="off" required>
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="passowrd" class="form-control" name="password" autocomplete="off">
						</div>
						<div class="form-group">
							<label>Foto</label>
							<input type="file" class="form-control" name="foto">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Edit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endforeach; ?>