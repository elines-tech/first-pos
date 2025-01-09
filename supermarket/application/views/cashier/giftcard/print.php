<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Gif Card Prints | <?= AppName ?></title>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<style>
		body {
			width: 100%;
			margin: 0 auto;
			padding: 0;
		}

		* {
			box-sizing: border-box;
		}

		.cardDiv {
			border: 2px solid #006605;
			width: 33%;
			height: 150px;
			border-radius: 10px;
			margin: 5px;
			background: #6b9b35;
			color: #ffffff;
			padding: 5px;
		}

		.mainDiv {
			width: 100%;
			display: flex;
			flex-direction: row;
		}

		.details {
			background: transparent;
			border-radius: 5px;
			padding: 2px 2px;
		}

		.buttonbar {
			width: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 15px auto;
			background: #ffffff;
			flex-direction: row;
		}

		.gifcardno {
			font-weight: 600;
			font-size: 1.5rem;
		}

		button {
			border-radius: none;
			outline: none;
			cursor: pointer;
			text-align: center;
			color: #ffffff;
			margin: 5px;
			padding: 5px 15px;
			border: none;
		}

		button#print {
			background-color: #3f51b5;
		}

		button#goback {
			background-color: #ff9800;
		}

		div.expired {
			width: 100%;
			padding: 5px 15px;
			color: #ffffff;
			background-color: #ff0000;
			text-align: center;
			font-weight: 600;
			font-size: 15px;
		}

		@media print {
			.cardDiv {
				color-adjust: exact;
				-webkit-print-color-adjust: exact;
			}

			small {
				font-size: 9px;
			}
		}
	</style>
	<script language="javascript">
		//Hide print button and pagination table when click on print button
		function hideall() {
			document.getElementById("print").style.display = "none";
			document.getElementById("goback").style.display = "none";
			var is_chrome = function() {
				return Boolean(window.chrome);
			}
			if (is_chrome) {
				window.print();
				setTimeout(function() {
					window.close();
				}, 10000);
			} else {
				document.getElementById("print").style.display = "block";
				document.getElementById("goback").style.display = "block";
				window.print();
				window.close();
			}
		}

		function printDiv(divName) {
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			document.body.innerHTML = originalContents;
		}

		window.onafterprint = function() {
			document.getElementById("print").style.display = "";
			document.getElementById("goback").style.display = "";
		}

		jQuery(document).bind("keyup keydown", function(e) {
			if (e.ctrlKey && e.keyCode == 80) {
				document.getElementById("print").style.display = "none";
				document.getElementById("goback").style.display = "none";
			}
		});

		function goback() {
			window.location.replace("<?= base_url("Cashier/giftCard/listRecords") ?>")
		}
	</script>
</head>

<?php include '../supermarket/config.php'; ?>


<body>
	<div class="buttonbar">
		<button type="button" id="print" style="background-color: #37B181;color: #ffffff;transition: none;margin-right: 10px;border: none;border-radius: 10px; padding:15px" onclick="hideall()"><?php echo $translations['Print']?></button>
		<button type="button" id="goback" style="background-color: red;color: #ffffff;transition: none;margin-right: 10px;border: none;border-radius: 10px; padding:15px" onclick="goback()"><?php echo $translations['Go Back']?></button>
	</div>
	<div class="m-2" id="printableArea">
		<div class="page-heading">
			<div class="mainDiv">
				<?php
				if (!empty($sale)) {
					$cardDetails = json_decode($sale['cardDetails'], true);
					$today = date("Y-m-d H:00:00");
					$expiryDate = $sale['expiryDate'];
					$title = $cardDetails['title'];
					$discount = $cardDetails['discount'];
					foreach ($cards as $card) {
						$cardHtml = '<div class="cardDiv">';
						$cardHtml .= '<div class="details">' . $title . '</div>';
						//$cardHtml .= '<div class="details"><img width="130" height="25" src="data:image/png;base64,' . $card['barcode'] . '"></div>';
						$cardHtml .= '<div class="details" style="text-align:center">' . ucwords(strtolower($card['custName'])) . '</div>';
						$cardHtml .= '<div class="gifcardno" style="text-align:center">' . $card['giftNo'] . '</div>';
						$cardHtml .= '<div class="details">Discount :<b>' . $cardDetails['discount'] . '%</b></div>';
						$cardHtml .= '<div class="details">Expiry Date : <b>' . date('d/m/Y', strtotime($expiryDate)) . '</b></div>';
						$cardHtml .= '<div class="note"><small>Can be used only once and only before Expiry date.</small></div>';
						if ($today > $sale['expiryDate']) {
							$cardHtml .= '<div class="expired">Gift-Card Expired</div>';
						}
						$cardHtml .= '</div>';
						echo $cardHtml;
					}
				}
				?>
			</div>
		</div>
	</div>
</body>

</html>