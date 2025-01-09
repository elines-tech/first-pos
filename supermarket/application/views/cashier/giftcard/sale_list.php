<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Sale History']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../Cashier/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gift-Card</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-5 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 order-md-1 order-last" id="leftdiv">
                                    <h5><?php echo $translations['Gift Details']?><span class="float-end"><a id="cancelDefaultButton" class="btn btn-sm btn-primary" href="<?= base_url() ?>Cashier/giftCard/listRecords"><?php echo $translations['Back']?></a></span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            $expiryDate = '';
                            if ($cardData) {
                                $result = $cardData->result()[0];
                                if ($result->validityInDays != '') {
                                    $expiryDate = date('Y-m-d', strtotime(" + " . $result->validityInDays . " days"));
                                }
                            ?>
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label for="batchNo"><b><?php echo $translations['Card Title']?></b></label>
                                        <input type="hidden" id="cardCode" name="cardCode" class="form-control-line" value="<?= $result->code ?>">
                                        <input type="text" id="cardTitle" name="cardTitle" class="form-control-line" value="<?= $result->title ?>">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="discount"><b><?php echo $translations['Discount (%)']?></b></label>
                                        <input type="text" id="discount" name="discount" class="form-control-line" value="<?= $result->discount ?>">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="price"><b><?php echo $translations['Price']?></b></label>
                                        <input type="text" id="price" name="price" class="form-control-line" value="<?= $result->price ?>">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="validityInDays"><b><?php echo $translations['Validity (In Days)']?></b></label>
                                        <input type="text" id="validityInDays" name="validityInDays" class="form-control-line" value="<?= $result->validityInDays ?>">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="description"><b><?php echo $translations['Description']?></b></label>
                                        <p><?= $result->description ?></p>
                                    </div>
                                    <form method="post" action="<?= base_url() ?>Cashier/giftCard/sale">
                                        <input type="hidden" class="form-control" name="giftCode" id="giftCode" value="<?= $result->code ?>">
                                        <button type="submit" id="view" class="btn btn-success btn-sm m-1 cursor_pointer"><i id="dlt" title="Add Sale" class="fa fa-plus"></i></button>
                                    </form>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 order-md-1 order-last" id="leftdiv">
                                    <h5><?php echo $translations['Giftcard History List']?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="print_div">
                            <table class="table table-striped table-responsive display" id="dataTableGiftcard">
                                <thead>
                                    <tr>
                                        <th><?php echo $translations['Sr No']?></th>
                                        <th><?php echo $translations['Customer Name']?></th>
                                        <th><?php echo $translations['Customer Email']?></th>
                                        <th><?php echo $translations['Customer Phone']?></th>
                                        <th><?php echo $translations['Quantity']?></th>
                                        <th><?php echo $translations['Total Price']?></th>
                                        <th><?php echo $translations['Expiry date']?></th>
                                        <th><?php echo $translations['Action']?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        getDataTable();
    });

    function getDataTable() {
        var cardCode = $('#cardCode').val();
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableGiftcard")) {
            $('#dataTableGiftcard').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableGiftcard').DataTable({
            stateSave: true,
            responsive: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Cashier/giftCard/getGiftCardHistoryList",
                data: {
                    'cardCode': cardCode
                },
                type: "GET",
                complete: function(response) {
                    $('.print_card').click(function() {
                        var code = $(this).data('seq');
                        $('#printDiv').html('')
                        $.ajax({
                            url: base_path + "Cashier/giftCard/printCard",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal').modal('show');
                                $('#printDiv').html(response)
                            }
                        });
                    });
                }
            }
        });
    }
</script>