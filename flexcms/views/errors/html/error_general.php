<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>

<style type="text/css">

	/* http://codepen.io/anon/pen/KddPqw */

	* {
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}

	html {
		height: 100%;
		color: #999;
	}

	body {
		background: #1B1B1B;
		position: relative;
		height: 100%;
		margin: 0px;
		font-family: "Helvetica";
		text-align: center;
	}

	@-webkit-keyframes clockwise {
		0% {
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	@-moz-keyframes clockwise {
		0% {
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	@-webkit-keyframes counter-clockwise {
		0% {
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-moz-transform: rotate(-360deg);
			-ms-transform: rotate(-360deg);
			-webkit-transform: rotate(-360deg);
			transform: rotate(-360deg);
		}
	}
	@-moz-keyframes counter-clockwise {
		0% {
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-moz-transform: rotate(-360deg);
			-ms-transform: rotate(-360deg);
			-webkit-transform: rotate(-360deg);
			transform: rotate(-360deg);
		}
	}
	.container {
		position: absolute;
		top: 50%;
		left: 50%;
		margin-left: -300px;
		height: 250px;
		width: 600px;
		margin-top: -125px;
	}

	.gearbox {
		height: 150px;
		margin: 0 auto;
		width: 200px;
		position: relative;
		border: none;
	}
	.gearbox .overlay {
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		border-radius: 6px;
		content: "";
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 10;
	}

	.gear {
		position: absolute;
		height: 60px;
		width: 60px;
		-moz-box-shadow: 0px -1px 0px 0px #888888, 0px 1px 0px 0px black;
		-webkit-box-shadow: 0px -1px 0px 0px #888888, 0px 1px 0px 0px black;
		box-shadow: 0px -1px 0px 0px #888888, 0px 1px 0px 0px black;
		-moz-border-radius: 30px;
		-webkit-border-radius: 30px;
		border-radius: 30px;
	}
	.gear.large {
		height: 120px;
		width: 120px;
		-moz-border-radius: 60px;
		-webkit-border-radius: 60px;
		border-radius: 60px;
	}
	.gear.large:after {
		height: 96px;
		width: 96px;
		-moz-border-radius: 48px;
		-webkit-border-radius: 48px;
		border-radius: 48px;
		margin-left: -48px;
		margin-top: -48px;
	}
	.gear.one {
		top: 12px;
		left: 10px;
	}
	.gear.two {
		top: 61px;
		left: 5px;
	}
	.gear.three {
		top: 110px;
		left: 10px;
	}
	.gear.four {
		top: 13px;
		left: 75px;
	}
	.gear:after {
		content: "";
		position: absolute;
		height: 36px;
		width: 36px;
		-moz-border-radius: 36px;
		-webkit-border-radius: 36px;
		border-radius: 36px;
		background: #333;
		top: 50%;
		left: 50%;
		margin-left: -18px;
		margin-top: -18px;
		z-index: 3;
		-moz-box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1), inset 0px 0px 10px rgba(0, 0, 0, 0.1), inset 0px 2px 0px 0px #080808, inset 0px -1px 0px 0px #888888;
		-webkit-box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1), inset 0px 0px 10px rgba(0, 0, 0, 0.1), inset 0px 2px 0px 0px #080808, inset 0px -1px 0px 0px #888888;
		box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.1), inset 0px 0px 10px rgba(0, 0, 0, 0.1), inset 0px 2px 0px 0px #080808, inset 0px -1px 0px 0px #888888;
	}

	.gear-inner {
		position: relative;
		height: 100%;
		width: 100%;
		background: #555;
		-webkit-animation-iteration-count: infinite;
		-moz-animation-iteration-count: infinite;
		-moz-border-radius: 30px;
		-webkit-border-radius: 30px;
		border-radius: 30px;
		border: 1px solid rgba(255, 255, 255, 0.1);
	}
	.large .gear-inner {
		-moz-border-radius: 60px;
		-webkit-border-radius: 60px;
		border-radius: 60px;
	}
	.gear.one .gear-inner {
		-webkit-animation: counter-clockwise 6s infinite linear;
		-moz-animation: counter-clockwise 6s infinite linear;
	}
	.gear.two .gear-inner {
		-webkit-animation: clockwise 6s infinite linear;
		-moz-animation: clockwise 6s infinite linear;
	}
	.gear.three .gear-inner {
		-webkit-animation: counter-clockwise 6s infinite linear;
		-moz-animation: counter-clockwise 6s infinite linear;
	}
	.gear.four .gear-inner {
		-webkit-animation: counter-clockwise 12s infinite linear;
		-moz-animation: counter-clockwise 12s infinite linear;
	}
	.gear-inner .bar {
		background: #555;
		height: 16px;
		width: 76px;
		position: absolute;
		left: 50%;
		margin-left: -38px;
		top: 50%;
		margin-top: -8px;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		border-radius: 2px;
		border-left: 1px solid rgba(255, 255, 255, 0.1);
		border-right: 1px solid rgba(255, 255, 255, 0.1);
	}
	.large .gear-inner .bar {
		margin-left: -68px;
		width: 136px;
	}
	.gear-inner .bar:nth-child(2) {
		-moz-transform: rotate(60deg);
		-ms-transform: rotate(60deg);
		-webkit-transform: rotate(60deg);
		transform: rotate(60deg);
	}
	.gear-inner .bar:nth-child(3) {
		-moz-transform: rotate(120deg);
		-ms-transform: rotate(120deg);
		-webkit-transform: rotate(120deg);
		transform: rotate(120deg);
	}
	.gear-inner .bar:nth-child(4) {
		-moz-transform: rotate(90deg);
		-ms-transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
	}
	.gear-inner .bar:nth-child(5) {
		-moz-transform: rotate(30deg);
		-ms-transform: rotate(30deg);
		-webkit-transform: rotate(30deg);
		transform: rotate(30deg);
	}
	.gear-inner .bar:nth-child(6) {
		-moz-transform: rotate(150deg);
		-ms-transform: rotate(150deg);
		-webkit-transform: rotate(150deg);
		transform: rotate(150deg);
	}

	h1 {
		text-transform: uppercase;
		font-size: 19px;
		padding-top: 10px;
	}



</style>

<body>

<div class="container">
	<div class="gearbox">

		<div class="gear two">
			<div class="gear-inner">
				<div class="bar"></div>
				<div class="bar"></div>
				<div class="bar"></div>
			</div>
		</div>

		<div class="gear four large">
			<div class="gear-inner">
				<div class="bar"></div>
				<div class="bar"></div>
				<div class="bar"></div>
				<div class="bar"></div>
				<div class="bar"></div>
				<div class="bar"></div>
			</div>
		</div>

	</div>
	<h1><?php echo $heading; ?></h1>
	<p><?php echo $message; ?></p>
</div>

</body>
</html>