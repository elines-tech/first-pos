<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Role Wise Rights</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Role Wise Rights</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<!-- Basic Tables start -->
		<section class="section">
			<div class="card">

				<div class="card-body">
					<div class="row">
						<div class="col-md-12 col-sm-4">
							<label class="form-label lng">Role</label>
							<p class="text-center">On this page you can define roles for all your users: Manager , Staff , Accounts, etc...</p>
							<div class="form-group mandatory">
								<select class="form-select select2" name="role" id="role" style="width:100%">
									<option value="">Select Role</option>
									<?php if ($roles) {
										foreach ($roles->result() as $ro) {
											echo '<option value="' . $ro->code . '">' . $ro->role . '</option>';
										}
									} ?>
								</select>
							</div>
						</div>
						<div class="col-md-12 col-sm-4">
							<div class="d-flex justify-content-center" style="margin-top:20px;">
								<button type="button" onclick="getMenuList()" class="btn btn-success" id="btnSearch">Search</button>
								<button type="button" onclick="clearSelection()" class="btn btn-success d-none" id="btnClear">Clear</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card d-none" id="rightsDiv">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
							<h5>View Management</h5>
						</div>
					</div>
				</div>
				<div class="card-body" id="menuHtml">

				</div>
				<div class="row">
					<div class="col-12 d-flex justify-content-end">
						<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" onclick="updateMenuRights()" id="submitBtn">Submit</button>

					</div>
				</div>
			</div>

		</section>
		<!-- Basic Tables end -->
	</div>
</div>
<script>
	function checkAll(id) {
		if ($('#allcheck' + id).is(":checked")) {
			$('.cb-element' + id).prop('checked', true);
		} else {
			$('.cb-element' + id).prop('checked', false);
		}
	}

	function checkAllSubcheck(submenus, id, startpt) {
		var start = Number(startpt) + Number(1)
		var end = Number(startpt) + Number(submenus)
		if ($('#allsubcheck' + id).is(":checked")) {
			for (i = start; i <= end; i++) {
				$('#allcheck' + i).prop("checked", true)
				checkAll(i)
			}
		} else {
			for (i = start; i <= end; i++) {
				$('#allcheck' + i).prop("checked", false)
				checkAll(i)
			}
		}
	}

	function validateAllCheck(id, submenus) {
		var checkedLength = $('.cb-element' + id + ':checked').length;
		if (checkedLength == 0) {
			$('#allcheck' + id).prop('checked', false);
		} else if (checkedLength == 4) {
			$('#allcheck' + id).prop('checked', true);
		}
	}

	function clearSelection() {
		if ($('#menuHtml').html() != '') {
			swal({
				title: "Are you sure?",
				text: "All the changes will be removed.. ",
				type: "warning",
				showCancelButton: !0,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: !1,
				closeOnCancel: !1
			}, function(e) {
				if (e) {
					$('#role').val('').trigger('change');
					$('#role').prop('disabled', false);
					$('#menuHtml').html('');
					$('#rightsDiv').addClass('d-none');
					$('#btnSearch').removeClass('d-none');
					$('#btnClear').addClass('d-none');
					swal.close()
				}
			});
		} else {
			$('#role').val('');
		}
	}

	function getMenuList() {
		var role = $('#role').val();
		if (role != '') {
			$('#btnClear').removeClass('d-none');
			$.ajax({
				type: 'post',
				url: base_path + "rolewiserights/getMenuList",
				data: {
					role: role,
				},
				beforeSend: function() {
					$('#btnSearch').prop('disabled', true);
					$('#btnSearch').text('Please wait..');
				},
				success: function(response) {
					$('#btnSearch').prop('disabled', false);
					$('#btnSearch').text('Search');
					var obj = JSON.parse(response);
					$('#role').prop('disabled', false);
					$('#btnSearch').removeClass('d-none');
					if (obj.status) {
						$('#rightsDiv').removeClass('d-none');
						$('#menuHtml').html(obj.menuHtml);
						$('#role').prop('disabled', true);
						$('#btnSearch').addClass('d-none');
						$('#menuHtml').html(obj.menuHtml);
					} else {
						$('#menuHtml').html('');
						$('#rightsDiv').addClass('d-none');
						toastr.success('Failed to get menulist', 'Role wise rights', {
							"progressBar": true
						});
					}
				}
			})
		} else {
			$('#btnClear').addClass('d-none');
			toastr.error('Please select role first', 'Role wise rights', {
				"progressBar": true
			});
			$('#role').focus();
			return false;
		}
	}

	function updateMenuRights() {
		var table = document.getElementById("rights_table");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var role = $('#role').val();
		var roleArr = [];
		if (role != '') {
			for (i = 1; i <= table_len; i++) {
				var menuArr = {};
				var id = tr[i].id.substring(3);
				var menu = $('#menu' + id).val();
				if ($('#view' + id).is(":checked")) {
					var isView = 1;
				} else {
					var isView = 0;
				}
				if ($('#insert' + id).is(":checked")) {
					var isInsert = 1;
				} else {
					var isInsert = 0;
				}
				if ($('#update' + id).is(":checked")) {
					var isUpdate = 1;
				} else {
					var isUpdate = 0;
				}
				if ($('#delete' + id).is(":checked")) {
					var isDelete = 1;
				} else {
					var isDelete = 0;
				}
				if ($('#default' + id).is(":checked")) {
					var isDefault = 1;
				} else {
					var isDefault = 0;
				}
				if (isView == 1 || isInsert == 1 || isUpdate == 1 || isDelete == 1 || isDefault == 1) {
					menuArr['menu'] = menu;
					menuArr['view'] = isView;
					menuArr['insert'] = isInsert;
					menuArr['update'] = isUpdate;
					menuArr['delete'] = isDelete;
					menuArr['default'] = isDefault;
					roleArr.push(menuArr);
				}
			}

			var finalRoleArray = JSON.stringify(roleArr)
			$.ajax({
				type: 'post',
				url: base_path + "rolewiserights/saveMenu",
				data: {
					role: role,
					finalRoleArray: finalRoleArray
				},
				beforeSend: function() {
					$('#submitBtn').prop('disabled', true);
					$('#cancelBtn').prop('disabled', true);
					$('#submitBtn').text('Please wait..');
				},
				success: function(data) {
					$('#submitBtn').text('Submit');
					$('#submitBtn').prop('disabled', false);
					$('#cancelBtn').prop('disabled', false);
					toastr.success('Rights updated successfully', 'Rights', {
						"progressBar": true
					});
					location.reload();
				}
			})

		}
	}
</script>