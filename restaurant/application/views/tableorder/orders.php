<style>
    .kot-order {
        background: #ffffff;
        margin: 5px 0;
        height: calc(85vh - 40px);
    }

    .kot-order-title {
        padding: 15px 10px;
        background: #8853a1;
        color: #ffffff;
    }

    .kot-order-products {
        padding: 10px 10px;
    }

    .kot-cooking-time {
        padding: 10px;
        background: #9c64b8;
    }

    .product-box {
        border-bottom: 2px solid #c7c1c1;
    }

    .prbx {
        background-color: #ffffff;
        padding: 5px 15px;
    }
</style>
<div class="row g-2" style="flex-wrap:nowrap;overflow-x:auto">
    <?php
    $no = 1;
    if (!empty($orders)) {
        for ($i = 0; $i < count($orders); $i++) {
            $order = $orders[$i];
    ?>
            <div class="col-12 col-sm-6 col-md-4" id="kot-order-<?= $order['id'] ?>">
                <div class="kot-order">
                    <div class="kot-order-title">
                        Order Number : <b><?= $no ?></b>
                        <div class="float-end">
                            <?php
                            if ($order['kotStatus'] == "PND") {
                            ?>
                                <button type="button" class="btn btn-sm btn-danger cancel-kot-order" data-cart-id="<?= $order['id'] ?>">Cancel Order</button>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="kot-cooking-time">
                        <div class="row text-light">
                            <?php
                            if ($order['kotStatus'] == "PND") {
                                $kotSection = '
                                    <div class="col-12 text-center"> Waiting <b class="" style="font-weight:bold;font-size:14px" id="count-down-' . $order['id'] . '">00:00</b></div>                                     
                                ';
                            } else if ($order['kotStatus'] == "PRE") {
                                $kotSection = '
                                    <div class="col-12 text-center"> Preparing <b class="" style="font-weight:bold;font-size:14px" id="count-down-' . $order['id'] . '">00:00</b></div>                                     
                                ';
                            } else if ($order['kotStatus'] == "RTS") {
                                $kotSection = '<div class="col-12 text-center"> Will be served soon... </div>';
                            }
                            echo $kotSection;
                            if ($order['kotStatus'] == "PND" || $order['kotStatus'] == "PRE") {
                            ?>
                                <script>
                                    var orId = "<?= $order['id'] ?>";
                                    var elementId = "count-down-" + orId;
                                    var orderAcceptTime = new Date('<?= $order['kotStatus'] == "PND" ? $order['cartDate'] : $order['preparingDateTime'] ?>');
                                    var preparingTime = parseInt('<?= $order['kotStatus'] == "PND" ? $order['waitingMinutes'] : $order['preparingMinutes'] ?>');
                                    orderAcceptTime.setMinutes(orderAcceptTime.getMinutes() + preparingTime);
                                    var nowDate = new Date();
                                    var x = setInterval(function() {
                                        var now = new Date().getTime();
                                        var distance = orderAcceptTime - now;
                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        document.getElementById(elementId).innerHTML = minutes + "m " + seconds + "s ";
                                        if (distance < 0) {
                                            clearInterval(x);
                                            document.getElementById(elementId).innerHTML = "Time Over";
                                            //document.getElementById(elementId).style.backgroundColor = "red";
                                        }
                                    }, 1000);
                                </script>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="prbx">
                        <div class="row g-0" style="max-height:280px;overflow-y:scroll">
                            <?php
                            $total = 0;
                            foreach ($order['products'] as $product) {
                                $total = $total + $product['totalPrice'];
                                $addons = json_decode($product['addOns'], true);
                                $comboProducts = json_decode($product['comboProducts'], true);
                            ?>
                                <div class="col-12 mb-2 product-box">
                                    <div class="product-title"><strong><?= $product['productName'] ?> x <b><?= $product['productQty'] ?></b></strong> <span class="float-end"><?= $product['productPrice'] ?></span></div>
                                    <?php if (!empty($addons)) { ?>
                                        <div class="addons">
                                            Addons :
                                            <table class="table">
                                                <?php
                                                foreach ($addons as $addon) {
                                                    $span = '<span class="float-end">' . $addon['addonPrice'] . '</span>';
                                                    echo "<tr><td>" . $addon['addonTitle'] . " X " . $addon['addonQty'] . "</td><td>" . $span . "</td></tr>";
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($comboProducts)) { ?>
                                        <div class="addons">
                                            Includes :<br>
                                            <?php
                                            foreach ($comboProducts as $cmb) {
                                                $productDetails = $this->db->query('select productmaster.productEngName from productmaster where code="' . $cmb['productCode'] . '"');
                                                if ($productDetails) {
                                                    $result = $productDetails->result()[0];
                                                    echo "<span class='badge bg-success' style='margin-right:2px;'>" . $result->productEngName . "</span>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                            <h5><strong>Total <span class="float-end"><?= number_format($total, 2, '.', ',') ?></span></h5>
                        </div>
                    </div>
                </div>
            </div>
    <?php
            $no++;
        }
    }
    ?>
</div>
<script>
    /*$(document).on("click", "button.collapsible", function (e) {
	e.preventDefault();
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });*/
</script>