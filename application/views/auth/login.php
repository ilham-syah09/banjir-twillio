<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-md-6">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <img src="<?= base_url('assets/img/phb-logo.png'); ?>" alt="logo" width="150" class="img-fluid">
                        <h1 class="h4 text-gray-900 mt-3 mb-4">Login Admin</h1>
                    </div>
                    <form class="user" method="POST" action="<?= base_url('auth/proses'); ?>">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control form-control-user" placeholder="Enter Username...">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>