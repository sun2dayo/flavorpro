<?php
// TAB: Colors (original Revolution Pro)
print '<div role="tabpanel" id="revoprocolors" class="tab-pane active show">';
print '<div class="site-skintools2">';
	print '<div class="site-skintools2-inner">';
	print '<div class="site-skintools2-content">';
	print '<div class="nav-tabs-horizontal">';
	print '<div class="tab-content">';
	print '<table class="tableconfigurationrevopro">';
	print '<tr>';
		print '<td>';
			print '<div>';
			print '<h4 class="site-skintools2-title">Sidebar Skins</h4>';
			$chd = ''; if($val1 == 'dark') $chd = 'checked';
			print '<div class="radio-custom radio-dark"><input id="skintoolsSidebar2-dark" type="radio" name="value1" '.$chd.' value="dark"><label for="skintoolsSidebar2-dark">dark</label></div>';
			$chd = ''; if($val1 == 'light') $chd = 'checked';
			print '<div class="radio-custom radio-light"><input id="skintoolsSidebar2-light" type="radio" name="value1" '.$chd.' value="light"><label for="skintoolsSidebar2-light">light</label></div>';
			print '</div>';
		print '</td>';
		print '<td>';
			print '<div>';
				print '<h4 class="site-skintools2-title">Navbar Type</h4>';
				$chd = ''; if($val2 == 'inverse') $chd = 'checked';
				print '<div class="checkbox-custom checkbox-inverse"><input id="skintoolsNavbar2-inverse" type="checkbox" name="value2" '.$chd.' value="inverse"><label for="skintoolsNavbar2-inverse">inverse</label></div>';
		print '</td>';
	print '</tr>';
	print '<tr>';
		print '<td colspan="2" id="colornavbar">';
			print '<div>';
				print '<h4 class="site-skintools2-title">Navbar Skins</h4>';
				foreach(array('primary','blue','brown','cyan','green','grey','orange','pink','purple','red','teal','yellow') as $c) {
					$chd = ($val3 == $c) ? 'checked' : '';
					print '<div class="radio-custom radio-'.$c.'"><input id="skintoolsNavbar2-'.$c.'" type="radio" name="value3" '.$chd.' value="'.$c.'"><label for="skintoolsNavbar2-'.$c.'">'.$c.'</label></div>';
				}
			print '</div>';
		print '<td>';
	print '</tr>';
	print '<tr>';
		print '<td colspan="2" id="colorbuttons">';
			print '<div>';
				print '<h4 class="site-skintools2-title">Buttons Color</h4>';
				foreach(array('primary','blue','brown','cyan','green','grey','orange','pink','purple','red','teal','yellow') as $c) {
					$chd = ($val6 == $c) ? 'checked' : '';
					print '<div class="radio-custom radio-'.$c.'"><input id="skintoolsNavbar3-'.$c.'" type="radio" name="value6" '.$chd.' value="'.$c.'"><label for="skintoolsNavbar3-'.$c.'">'.$c.'</label></div>';
				}
			print '</div>';
		print '<td>';
	print '</tr>';
	print '</table>';
	print '</div>';
	print '</div>';
	print '</div>';
	print '</div>';
print '</div>';
print '</div>';
