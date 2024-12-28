<div id="sidebar" class="active">
	<div class="sidebar-wrapper active">
		<div class="sidebar-header position-relative">
			<div class="align-items-center">
				<div class="logo text-center">
					<!--<a href="#"><img src="<?= base_url() ?>assets/images/logo/logomain.png" alt="Logo" srcset=""></a>-->
					<a href="#"><img src="<?= base_url() ?>assets/images/logo/Group.svg" alt="Logo" srcset=""></a>
				</div>
				<div class="sidebar-toggler  x">
					<a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
				</div>
			</div>
		</div>
		<div class="sidebar-menu">
			<ul class="menu">
				<?php
				$session_key = $this->session->userdata('key' . SESS_KEY);
				$roleCode = $this->session->userdata['logged_in' . $session_key]['rolecode'];
				$filecontents = file_get_contents('assets/rights/menu.json');
				$rightscontents = file_get_contents('assets/rights/' . $roleCode . '.json');
				$menuJson = json_decode($filecontents, true);
				$rightJson = json_decode($rightscontents, true);
				$rightsMenu = [];
				foreach ($rightJson as $rt) {
					array_push($rightsMenu, explode('.', $rt['menu'])[0]);
				}
				$menuSeq = array_column($menuJson, 'seq');
				array_multisort($menuSeq, SORT_ASC, $menuJson);
				foreach ($menuJson as $menu) {
					if (in_array($menu['id'], $rightsMenu)) {
						if ($menu['type'] == 1) {
				?>
							<li class="sidebar-item has-sub"><a href="#" class='sidebar-link'><i class="<?= $menu['icon'] ?>"></i><span><?= $menu['name'] ?></span></a>
								<ul class="submenu ">
									<?php
									$submenuSeq = array_column($menu['submenu'], 'seq');
									array_multisort($submenuSeq, SORT_ASC, $menu['submenu']);
									foreach ($menu['submenu'] as $submenu) {
										if (array_search($submenu['id'], array_column($rightJson, 'menu')) !== FALSE) {
									?>
											<li class="submenu-item"><a href="<?= base_url(); ?><?= $submenu['url'] ?>" class="side-anchors" data-attr="<?= $submenu['data-attr'] ?>"><?= $submenu['name'] ?></a></li>
									<?php  }
									} ?>
								</ul>
							</li>
						<?php } else {
							$submenu = $menu['submenu'][0];
						?>
							<li class="sidebar-item"><a href="<?= base_url(); ?><?= $submenu['url'] ?>" class='sidebar-link'><i class="<?= $submenu['icon'] ?>"></i><span><?= $submenu['name'] ?></span></a></li>
						<?php } ?>
				<?php
					}
				} ?>
			</ul>
		</div>
	</div>
</div>