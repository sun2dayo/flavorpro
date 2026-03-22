<?php
// TAB: Login Page (original Revolution Pro)
print '<div role="tabpanel" id="revoprologin" class="tab-pane">';
print '<div class="site-skintools2" >';
	print '<div class="site-skintools2-inner">';
	print '<div class="site-skintools2-content">';
	print '<div class="nav-tabs-horizontal">';
	print '<br>';
	print '<div class="tab-content">';
	print '<table class="tableconfigurationrevopro">';

	require_once DOL_DOCUMENT_ROOT.'/core/class/doleditor.class.php';
	$form=new Form($db);
	$substitutionarray=getCommonSubstitutionArray($langs, 0, array('object','objectamount','user'));
	complete_substitutions_array($substitutionarray, $langs);
	print '<tr class=""><td style="width: 250px;">';
	$texthelp=$langs->trans("FollowingConstantsWillBeSubstituted").'<br>';
	foreach($substitutionarray as $key => $val) { $texthelp.=$key.'<br>'; }
	print $form->textwithpicto($langs->trans("MessageLogin"), $texthelp, 1, 'help', '', 0, 2, 'tooltipmessagelogin');
	print '</td><td >';
	$doleditor = new DolEditor('main_home', (isset($conf->global->MAIN_HOME)?$conf->global->MAIN_HOME:''), '', 142, 'dolibarr_notes', 'In', false, true, true, ROWS_4, '99%');
	$doleditor->Create();
	print '</td></tr>'."\n";
	print '<tr class=""><td></td></tr>'."\n";
	print '</table>';
	print '</div>';

	print '<br>';
	print '<div class="uploadimagelogin">';
	print '<ul class="blocks blocks-100 blocks-xxl-4 blocks-lg-3 blocks-md-2" data-plugin="filterable" data-filters="#exampleFilter">';

	$urlimgs = dol_buildpath('/revolutionpro/img/login', 1);
	$i = 1;
	// Login image 1
	$nameoffile = 'revolutionprologin1.jpg';
	print '<li data-type="bg">';
	$chd = ''; if($val5 == $nameoffile) $chd = 'selected';
	$urlf = $urlimgs."/".$nameoffile;
	print '<div class="card '.$chd.' card-shadow"><figure class="card-img-top overlay-hover overlay" style="min-height: 272px;"><img class="overlay-figure overlay-scale" src="'.$urlf.'" alt="..."><figcaption class="overlay-panel overlay-background overlay-fade overlay-icon"><a class="icon md-search pictopreview documentpreview" href="'.$urlf.'" mime="image/jpeg"></a></figcaption></figure><div class="card-block revolutionprofilename">';
	$chd = ''; if($val5 == $nameoffile) $chd = 'checked';
	print '<div class="radio-custom radio-blue"><input class="radiologinbg" '.$chd.' id="loginbg-hide'.$i.'" type="radio" name="value5" value="'.$nameoffile.'"><label for="loginbg-hide'.$i.'">'.$langs->trans('Image').' #'.$i.'</label></div>';
	print '</div></div></li>';

	$i++;
	// Login image 2
	$nameoffile = 'revolutionprologin2.jpg';
	print '<li data-type="bg">';
	$chd = ''; if($val5 == $nameoffile) $chd = 'selected';
	$urlf = $urlimgs."/".$nameoffile;
	print '<div class="card '.$chd.' card-shadow"><figure class="card-img-top overlay-hover overlay" style="min-height: 272px;"><img class="overlay-figure overlay-scale" src="'.$urlf.'" alt="..."><figcaption class="overlay-panel overlay-background overlay-fade overlay-icon"><a class="icon md-search pictopreview documentpreview" href="'.$urlf.'" mime="image/jpeg"></a></figcaption></figure><div class="card-block revolutionprofilename">';
	$chd = ''; if($val5 == $nameoffile) $chd = 'checked';
	print '<div class="radio-custom radio-blue"><input class="radiologinbg" '.$chd.' id="loginbg-hide'.$i.'" type="radio" name="value5" value="'.$nameoffile.'"><label for="loginbg-hide'.$i.'">'.$langs->trans('Image').' #'.$i.'</label></div>';
	print '</div></div></li>';
	$i++;

	// Uploaded images
 	$filearray_login = dol_dir_list($upload_dir, "files", 0, '', null, 'date', '', 0);
 	$modulepart_login = 'mycompany';
	foreach ($filearray_login as $key => $file) {
		if (!is_dir($file['name']) && $file['name'] != '.' && $file['name'] != '..' && $file['name'] != 'CVS' && !preg_match('/\.meta$/i', $file['name'])) {
			$documenturl = DOL_URL_ROOT.'/document.php';
			if (isset($conf->global->DOL_URL_ROOT_DOCUMENT_PHP)) $documenturl = $conf->global->DOL_URL_ROOT_DOCUMENT_PHP;
			$relativepath = '/logos/login/1/'.$file["name"];
			$urladvancedpreview = getAdvancedPreviewUrl($modulepart_login, $relativepath, 1, $param);
			$urlf = $documenturl.'?modulepart='.$modulepart_login.'&amp;file='.urlencode($relativepath).($param ? '&'.$param : '').'"';
			$nameoffile = urlencode($file["name"]);
			print '<li data-type="bg">';
			$chd = ''; if($val5 == $nameoffile) $chd = 'selected';
			print '<div class="card '.$chd.' card-shadow"><figure class="card-img-top overlay-hover overlay" style="min-height: 272px;"><img class="overlay-figure overlay-scale" src="'.$urlf.'" alt="..."><figcaption class="overlay-panel overlay-background overlay-fade overlay-icon"><a class="icon md-search pictopreview documentpreview" href="'.$urlf.'" mime="'.$urladvancedpreview['mime'].'"></a></figcaption></figure><div class="card-block revolutionprofilename">';
			$chd = ''; if($val5 == $nameoffile) $chd = 'checked';
			print '<div class="radio-custom radio-blue"><input class="radiologinbg" '.$chd.' id="loginbg-hide'.$i.'" type="radio" name="value5" value="'.$nameoffile.'"><label for="loginbg-hide'.$i.'">'.$langs->trans('Image').' #'.$i.'</label></div>';
			print '</div></div></li>';
			$i++;
		}
	}

	print '</ul>';
	print '</div>';
	print '<div style="clear:both;"></div>';
	print '</div>';
	print '</div>';
	print '</div>';
print '</div>';
print '</div>';
