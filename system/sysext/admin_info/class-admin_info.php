<?php
/******************************
 * Knowledgeroot
 * Frank Habermann
 * 21.09.2006
 *
 * Version 0.1
 * This Class shows informations in the admin interface
 ******************************/

class admin_info extends extension_base {

	function main() {
		$content = "";

		// add menu item to admin navi
		$this->menu['admin']['info']['name'] = $this->CLASS['translate']->_('information');
		$this->menu['admin']['info']['link'] = "index.php?action=show_info";
		$this->menu['admin']['info']['priority'] = "10";

		// add logout
		$this->menu['admin']['logout']['name'] = $this->CLASS['translate']->_('logout');
		$this->menu['admin']['logout']['link'] = "index.php?action=logout";
		$this->menu['admin']['logout']['priority'] = "90";

		// check for logout
		if(isset($_GET['action']) and $_GET['action'] == "logout") {
			$this->logout();
		}

		// check if informations should be shown
		if(isset($_GET['action']) and $_GET['action'] == "show_info") {
			$content = $this->show_info();
		}

		// set default content for admin interface
		$this->CLASS['kr_extension']->default_content = $this->show_info();

		return $content;
	}

	// show informations
	function show_info() {
		include($this->CLASS['config']->base->base_path.'/include/version.php');
		$out = '';
		$out .= '<div  class="jumbotron">';
		$out .= '<h1 class="display-4">';
		$out .= 'Knowledgeroot ' . $version;
		$out .= '</h1>';
		$out .= '<p class="lead">';
		$out .= 'Knowledgeroot Knowledgebase';
		$out .= '</p>';
        $out .= '</div>';

        $out .= '</p>';

		$out .= '<div class="card">';
        $out .= '<div class="card-header">';
		$out .= 'Environment';
		$out .= '</div>';
        $out .= '<div class="card-body">';
		$out .= 'PHP Version: '.PHP_VERSION.'<br />';
		$out .= 'OS: '.PHP_OS.'<br />';
		$out .= 'Server Software: '.$_SERVER['SERVER_SOFTWARE'].'<br />';
		$out .= '</div>';
        $out .= '</div>';

        $out .= '</p>';

        $out .= '<div class="card">';
        $out .= '<div class="card-header">';
        $out .= 'Database';
        $out .= '</div>';
        $out .= '<div class="card-body">';
		$out .= 'Type: '.$this->CLASS['config']->db->adapter.'<br />';
		$out .= 'Host: '.$this->CLASS['config']->db->params->host.'<br />';
		$out .= 'User: '.$this->CLASS['config']->db->params->username.'<br />';
		$out .= 'Databasename: '.$this->CLASS['config']->db->params->dbname.'<br />';
        $out .= '</div>';
        $out .= '</div>';

        $out .= '</p>';

        $out .= '<div class="card">';
        $out .= '<div class="card-header">';
        $out .= 'Extensions';
        $out .= '</div>';
        $out .= '<div class="card-body">';
		$out .= 'Active Extensions: <br />';
		$res = $this->CLASS['db']->query('SELECT keyname FROM extensions WHERE active=1');
		$first = true;
		while($row = $this->CLASS['db']->fetch_assoc($res)) {
			if($first) {
				$first = false;
				$out .= $row['keyname'];
			} else {
				$out .= ', ' . $row['keyname'];
			}
		}
        $out .= '</div>';
        $out .= '</div>';

        $out .= '</p>';

        $out .= '<div class="card">';
        $out .= '<div class="card-header">';
        $out .= 'Statistic';
        $out .= '</div>';
        $out .= '<div class="card-body">';
		$res = $this->CLASS['db']->query('SELECT count(id) as anz FROM users WHERE deleted=0');
		$row = $this->CLASS['db']->fetch_assoc($res);
		$out .= 'Users: '.$row['anz'].'<br />';
		$res = $this->CLASS['db']->query('SELECT count(id) as anz FROM groups WHERE deleted=0');
		$row = $this->CLASS['db']->fetch_assoc($res);
		$out .= 'Groups: '.$row['anz'].'<br />';
		$res = $this->CLASS['db']->query('SELECT count(id) as anz FROM tree WHERE deleted=0');
		$row = $this->CLASS['db']->fetch_assoc($res);
		$out .= 'Pages: '.$row['anz'].'<br />';
		$res = $this->CLASS['db']->query('SELECT count(id) as anz FROM content WHERE deleted=0');
		$row = $this->CLASS['db']->fetch_assoc($res);
		$out .= 'Contents: '.$row['anz'].'<br />';
		$res = $this->CLASS['db']->query('SELECT count(id) as anz FROM files WHERE deleted=0');
		$row = $this->CLASS['db']->fetch_assoc($res);
		$out .= 'Files: '.$row['anz'].'<br />';
        $out .= '</div>';
        $out .= '</div>';

        $out .= '</p>';

		return $out;
	}

	function logout() {
		$_SESSION = array();

		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		session_destroy();
		header("Location: index.php");
		exit();
	}
}

?>
