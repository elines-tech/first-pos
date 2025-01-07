<?php
if (!empty($kotOrders)) {
    for ($i = 0; $i < count($kotOrders); $i++) {
        $order = $kotOrders[$i];
?>

        <?php include '../restaurant/config.php'; ?>


        <div class="col-sm-6 col-md-4" id="kot-order-box-<?= $order['id'] ?>">
            <div class="kotcard">
                <div class="kot-header">
                    <div class="row">
                        <div class="col-sm-6 col-6"><?php echo $translations['KOT No'] ?></div>
                        <div class="col-sm-6 col-6"><span><?= $order['kotNumber'] ?></span></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-6"><?php echo $translations['Sector']?></div>
                        <div class="col-sm-6 col-6"><span><?= $order['zoneName'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-6"><?php echo $translations['Table No']?></div>
                        <div class="col-sm-6 col-6"><span><?= $order['tblNo'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-6"><?php echo $translations['Phone']?></div>
                        <div class="col-sm-6 col-6"><span><?= $order['custPhone'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-6"><?php echo $translations['Name']?></div>
                        <div class="col-sm-6 col-6"><span><?= $order['custName'] ?></span></div>
                    </div>
                </div>
                <div class="cooking-time">
                    <?php
                    $kotSection = $statusButton = "";
                    switch ($order['kotStatus']) {
                        case "PND":
                            $statusButton = '<button type="button" id="saveDefault" class="btn btn-default btn-sm btn-preparing" data-cart-id="' . $order['id'] . '"> Preparing </button>';
                            $kotSection = '<div class="text-center text-light"><b>Waiting for your action...</b></div>';
                            break;
                        case "PRE":
                            $statusButton = '<button type="button" id="ready" class="btn btn-default btn-sm btn-ready-serve" data-cart-id="' . $order['id'] . '"> Ready to Serve </button>';
                            $kotSection = '<div class="row text-light">
                                <div class="col-6"> Preparing In </div> 
                                <div class="col-6"><b class="time badge badge-light" style="font-weight:bold;font-size:14px" id="kot-order-' . $order['id'] . '">00:00</b></div>
                            </div>';
                            break;
                    }
                    echo $kotSection;
                    if ($order['kotStatus'] == "PRE") {
                    ?>
                        <script>
                            var orId = "<?= $order['id'] ?>";
                            var elementId = "kot-order-" + orId;
                            var orderAcceptTime = new Date('<?= $order['preparingDateTime'] ?>');
                            var preparingTime = parseInt('<?= $order['preparingMinutes'] ?>');
                            orderAcceptTime.setMinutes(orderAcceptTime.getMinutes() + preparingTime);
                            var nowDate = new Date();
                            console.log("Cooking Time", orderAcceptTime);
                            console.log("Current Time", nowDate);
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
                                    document.getElementById(elementId).innerHTML = "EXP";
                                    document.getElementById(elementId).style.backgroundColor = "red";
                                }
                            }, 1000);
                        </script>
                    <?php } ?>
                </div>
                <div class="kot-footer text-center">
                    <?= $statusButton ?>
                    <button id="viewOrder" type="button" class="btn btn-sm btn-primary ml-1 view-order-details" data-kotnumber="<?= $order['kotNumber'] ?>" data-cart-id="<?= $order['id'] ?>">View Order</button>
                </div>
            </div>
        </div>
<?php
    }
}
?>