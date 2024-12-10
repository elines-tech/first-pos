    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5 catproduct">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Orders-Kitchen</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="tab-content bg-light kitorder" id="myTabContent">
                                        <div class="container tab-pane fade show active" id="allcat" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="ref row" style="position:relative; z-index:10;">
                                                <?php
                                                if ($ordersData) {
                                                    $srno = 0;
                                                    foreach ($ordersData as $r) { ?>
                                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-p-3 d-inblock">
                                                            <div class="modalheader bg-info container">
                                                                <div class="food_item_top">
                                                                    <div class="row mb-2 item_inner">
                                                                        <h4 class="col-md-6 kf_info text-left"><strong>Table No. :</strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-right"><strong><?= $r['tableCode'] ?></strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-left"><strong>Order No. :</strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-right"><strong> <?= $r['orderCode'] ?></strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-left"><strong>Customer:</strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-right"><strong> Walkin</strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-left"><strong>Date :</strong></h4>
                                                                        <h4 class="col-md-6 kf_info text-right"><strong><?= date('d-m-Y h:i A', strtotime($r['orderDate'])) ?> </strong></h4>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if ($r['orderStatus'] == 'PRO') { ?>
                                                                <script>
                                                                    debugger
                                                                    var elementId = "order_<?= $r['orderCode'] ?>";
                                                                    var orderAcceptTime = new Date('<?= $r['prepareDateTime'] ?>');
                                                                    var preparingTime = parseInt('<?= $r['preparingMinutes'] ?>');
                                                                    var mi = orderAcceptTime.getMinutes();
                                                                    var countDownDate = orderAcceptTime.setMinutes(mi + preparingTime);

                                                                    var x = setInterval(function() {

                                                                        var now = new Date().getTime();
                                                                        var distance = countDownDate - now;
                                                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                                        document.getElementById(elementId).innerHTML = minutes + "m " + seconds + "s ";
                                                                        if (distance < 0) {
                                                                            clearInterval(x);
                                                                            document.getElementById(elementId).innerHTML = "EXP";
                                                                            document.getElementById(elementId).style.backgroundColor = "red";
                                                                        }
                                                                    }, 1000);
                                                                </script>
                                                            <?php } ?>
                                                            <div class="cooking_time">
                                                                <h4 class="kf_info">Cooking Time: <?= $r['preparingMinutes'] ?> m
                                                                    <?php if ($r['orderStatus'] == 'PRO') { ?>
                                                                        <span class="ms-1" id="order_<?= $r['orderCode'] ?>">
                                                                            00:00
                                                                        </span> <?php } ?>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-body kitbody">
                                                                <form class="extablk" method="post">
                                                                    <div class="row1"></div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="form-group mandatory mt-2">
                                                                                <label class="form-label lng">Order Status</label>
                                                                                <select class="form-select" id="orderStatus" name="orderStatus">
                                                                                    <?php if ($orderstatus) {
                                                                                        foreach ($orderstatus->result() as $status) {
                                                                                            $selected = $r['orderStatus'] == $status->statusSName ? 'selected' : '';
                                                                                            echo '<option value="' .  $status->statusSName . '"' . $selected . '>' .  $status->statusName . '</option>';
                                                                                        }
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="lastdiv">
                                                                        <?php if ($r['orderStatus'] == 'PND') { ?>
                                                                            <a class="btn btn-primary lng sub_2 actionBtn" data-id="<?= $r['orderCode'] ?>" data-action="Update order to processing food?" data-status="PRO">Pending</a>
                                                                        <?php } else if ($r['orderStatus'] == 'PRO') { ?>
                                                                            <a class="btn btn-success lng sub_2 actionBtn" data-id="<?= $r['orderCode'] ?>" data-action="Update order to Ready to serve?" data-status="RTS">Processing</a>
                                                                        <?php } else if ($r['orderStatus'] == 'RTS') { ?>
                                                                            <a class="btn btn-info lng sub_2 actionBtn">Ready to serve</a>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-info lng sub_2 actionBtn">Confirm</a>
                                                                        <?php } ?>

                                                                        <a data-seq="<?= $r['orderCode'] ?>" class="btn btn-primary lng sub_2 orderDetails">Order Details</a>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                <?php }
                                                } ?>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->

    <div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="page-heading">
                        <div class="page-title">
                            <div class="row">
                                <div class="col-12 col-md-6 order-md-1 order-last">
                                    <h3>Orders</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 mb-2" id="rightdiv">
                            <div class="btn-group btn-group-sm " role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary sub_1" onclick="printDiv('print_div')">Print</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 mx-auto panel-body" id="print_div">

                    </div>
                </div>

            </div>
        </div>
    </div>