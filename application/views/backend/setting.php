<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    Untuk setting selenoid valve
                </div>
                <form action="<?= base_url('setting/changeSelenoid'); ?>" method="post">
                    <div class="form-group">
                        <label>Status</label>
                        <input type="hidden" name="id" value="<?= $setting->id; ?>">
                        <select class="form-control" name="relay">
                            <option value="OFF" <?= ($setting->relay == 'OFF' ? 'selected' : ''); ?>>OFF</option>
                            <option value="ON" <?= ($setting->relay == 'ON' ? 'selected' : ''); ?>>ON</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">save</button>
                </form>
            </div>
        </div>
    </div>
</div>