<!-- 
	Name: Menghong Chhay
	Student ID: 1210470
	Description: html file for movie review
	Extra feature: all of them
-->

<?php
	// get the film name requested
	$movie = $_GET["film"];
	
	// test if the movie requested exists or not
	if (!file_exists($movie)) {
		// go to page with error message
		create_error($movie);
	} else {
		create_page($movie);
	}
 
	function create_page($movie) { 
		// store movie related information
		$info_array = file("$movie/info.txt", FILE_IGNORE_NEW_LINES);
		list($title, $year, $rating) = $info_array;
		// store image of the movie
		$overview_image = "$movie/overview.png";
		if ($rating >= 60) {
			$rotten_image = "freshlarge.png";
		} else {
			$rotten_image = "rottenlarge.png";
		}
		// store overview's content
		$overview_file = "$movie/overview.txt";
		// get all the review
		$review_list = glob("$movie/review*.txt");
		$num_review = count($review_list);
		// decide how many reviews to view
		if (!isset($_GET["reviews"])) {
			$review_requested = $num_review;
		} else {
			$review_requested = $_GET["reviews"];
		}
		// begin_page used to make sure that if 0 review is requested
		// 0-0 of total reviews
		$begin_page = 1;
		if ($review_requested <= 0) {
			$review_requested = 0;
			$begin_page = 0;
		} elseif ($review_requested >= $num_review) {
			$review_requested = $num_review;
		} 
		$end_first_half = 0;
		if ($review_requested % 2 == 0) {
			$end_first_half = $review_requested / 2; 
		} else {
			$end_first_half = ($review_requested + 1) / 2;
		}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Rancid Tomatoes - <?=$title?></title>
		<meta charset="utf-8" />
		<meta name="description" content="movies review page"/>
		<meta name="keywords" content="overview, movie, rating, rotten, fresh, critic"/>
		<link href="movie.css" type="text/css" rel="stylesheet" />
		<link rel="shortcut icon" type="image/jpg" href="https://webster.cs.washington.edu/images/rotten.gif">
		
	</head>

	<body>
		<?php
			create_banner(1);
		?>

		<h1><?=$title?> (<?=$year?>)</h1>
		
		<div id="mainContent">
			<?php					
				create_rotten_heading($rotten_image, $rating, $num_review);
			?>

			<div id="rightContent">
				<div>
					<img src="<?=$overview_image?>" alt="general overview" />
				</div>
				<dl>
					<?php
						get_overview_content($overview_file);
					?>
				</dl>
			</div>

			<!--leftContent*/-->
			<div id="leftContent">
				<div class="column">
					<?php
						create_review($review_list, 0, $end_first_half);
					?>
				</div>

				<div class="column">
					<?php
						create_review($review_list, $end_first_half, $review_requested);
					?>
				</div>
			</div>
			<p id="pageN">(<?=$begin_page?>-<?=$review_requested?>) of <?=$num_review?></p>
			<?php
				create_rotten_heading($rotten_image, $rating, $num_review);
			?>
		</div>
		<?php
			create_banner(2);
		?>
	</body>
</html>
<?php
	}

	// create content
	function get_overview_content($overview_file) {
		$overview_info = file("$overview_file", FILE_IGNORE_NEW_LINES);
		foreach ($overview_info as $info) {
			$info_split = explode(":", $info);
			list($dt, $dd) = $info_split;
?>
			<dt><?=$dt?></dt>
			<dd><?=$dd?></dd> 
		<?php
		}
	}

	// Create review content
	function create_review($review_list, $start, $finish) {
		for($i = $start; $i < $finish; $i++) {
			$review_content = file($review_list[$i], FILE_IGNORE_NEW_LINES);
			list($review, $rating_pic, $reviewer, $publication) = $review_content;
			$rating_pic = strtolower($rating_pic);
		?>
			<div class="reviewBox">
				<p class="review">
					<!--<img src="https://webster.cs.washington.edu/images/<?=$rating_pic?>.gif" alt="<?=$rating_pic?>" />-->
					<q><?=$review?></q>
				</p>
				<p>
					<!-- <img src="https://webster.cs.washington.edu/images/critic.gif" alt="Critic" /> -->
					<?=$reviewer?> <br />
					<span class="publication"> <?=$publication?> </span>
				</p>
			</div>
		<?php
		}
	}

	// Create banner
	function create_banner($bannerNumber) {
		?>
		<div id="centerBanner<?=$bannerNumber?>" class="banner">
			<img src="https://webster.cs.washington.edu/images/rancidbanner.png" alt="Rancid Tomatoes" />
		</div>
	<?php
	}

	// Create rottenHeading
	function create_rotten_heading($rotten_image, $rating, $num_review) {
	?>
		<div class="rottenHeading">
			<!-- <img src="https://webster.cs.washington.edu/images/<?=$rotten_image?>" alt="Rotten" /> -->
			<span class="text33"><?=$rating?><span class="addOn">% (out of <?=$num_review?> reviews)</span></span>
		</div>
	<?php
	}

	// create error page
	function create_error($movie) {
	?>
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8" />
				<meta name="description" content="movies review page"/>
				<meta name="keywords" content="overview, movie, rating, rotten, fresh, critic"/>
				<title>Error Page</title>
			</head>
			<body>
				<p> Film not found: <?=$movie?></p>
			</body>
		</html>
	<?php
	}
?>