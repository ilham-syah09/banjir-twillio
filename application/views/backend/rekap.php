<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
<!-- DataTales Example -->
<div class="card border-left-success shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-border table-hover" id="examples">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ketinggian</th>
                        <th>Status</th>
                        <th>Debit</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($rekap as $data) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $data->ketinggian; ?></td>
                            <td>
                                <?php if ($data->status == "AMAN") : ?>
                                    <span class="badge badge-success"><?= $data->status; ?></span>
                                <?php elseif ($data->status == "SIAGA") : ?>
                                    <span class="badge badge-warning"><?= $data->status; ?></span>
                                <?php else : ?>
                                    <span class="badge badge-danger"><?= $data->status; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= $data->debit; ?></td>
                            <td><?= date('d-F-Y H:i:s', strtotime($data->date)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>