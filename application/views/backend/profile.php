<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
<!-- DataTales Example -->
<div class="row">
    <div class="col-md-6">
        <div class="card border-left-success shadow mb-4">
            <div class="card-body">
                <a href="" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editProfile<?= $this->dt_admin->id; ?>"><i class="fas fa-edit"></i></a>
                <div class="text-center">
                    <img src="<?= base_url('upload/' . $this->dt_admin->foto); ?>" alt="foto" width="150" class="img-fluid rounded-corner">
                    <h3 class="mt-2"><?= $this->dt_admin->nama; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfile<?= $this->dt_admin->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('profile/editprofile'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $this->dt_admin->id; ?>">
                    <input type="hidden" name="previmage" value="<?= $this->dt_admin->foto; ?>">
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" value="<?= $this->dt_admin->nama; ?>">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" value="<?= $this->dt_admin->username; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>