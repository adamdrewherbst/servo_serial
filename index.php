<?
extract($_GET);
?>

<html>
	<head>
		<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript">

			function runProgram() {
				$('#ready-light').hide();
				$('input[type=number]').each(function() {
					var $this = $(this), val = parseFloat($this.val()), min = $this.attr('min'), max = $this.attr('max');
					if(min) {
						min = parseInt(min);
						if(min && val < min) $this.val(min);
					}
					if(max) {
						max = parseInt(max)
						if(max && val > max) $this.val(max);
					}
				});
				var instructions = $('#program li'), program = [];
				for(var i = 0; i < instructions.length; i++) {
					var instruction = $(instructions[i]), value = instruction.find('input').val();
					if(instruction.hasClass('motor-speed')) {
						program.push(['speed', value]);
					} else if(instruction.hasClass('motor-angle')) {
						program.push(['angle', value]);
					} else if(instruction.hasClass('motor-delay')) {
						program.push(['delay', value]);
					}
				}
				//send them to the Arduino
				$.ajax({
					url: 'motor.py',
					type: 'POST',
					dataType: 'json',
					data: {
						program: program,
					},
					success: function(data) {
						console.info(data);
					},
					error: function(xhr, status, error) {
						console.error('ajax error - ' + status + ': ' + error);
						console.error(xhr.responseText);
					},
					complete: function() {
						$('#ready-light').show();
					},
				});
			}

			$(document).ready(function() {
				$('#program').sortable({
					revert: true,
				}).droppable({greedy:true});
				$('li.draggable').draggable({
					connectToSortable: '#program',
					helper: 'clone',
					revert: 'invalid',
				});
				$('body').droppable({
					drop: function(event, ui) {
						if(ui.draggable.parent().attr('id') == 'program') {
							ui.draggable.remove();
						}
					}
				});
				//$('ul,li').disableSelection();
			});
		</script>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<style>
			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				margin-bottom: 10px;
			}
			li {
				margin: 5px;
				padding: 5px;
				width: 600px;
			}

			body {
				margin-top: 50px;
				height: 100%;
				overflow: hidden;
			}

			div.wrapper {
				padding: 15px;
			}

			h3 {
				margin-top: 0;
			}

			#program-wrapper {
				float: left;
				border: 3px solid #77c;
				border-radius: 10px;
				width: 45%;
			}
			#program {
				min-height: 300px;
			}
			#run-button {
				float: right;
				background-color: #6c6;
				font-size: 20px;
				padding: 5px;
			}
			#ready-light {
				float: right;
				background-color: #bfb;
				color: #008;
				margin-top: 5px;
				margin-right: 15px;
				text-transform: uppercase;
				display: none;
			}

			#instructions-wrapper {
				float: right;
				top: 50px;
				right: 50px;
				border: 3px solid #c77;
				border-radius: 10px;
				width: 45%;
			}

			.draggable {
				cursor: pointer;
				user-select: none;
			}
			.draggable input {
				user-select: auto;
			}
			.draggable input[type=range] {
				width: 50%;
			}
			.draggable .slider-label {
				font-weight: bold;
				margin: 0 5%;
			}
		</style>
	</head>
	<body>
		<div id="instructions-wrapper" class="wrapper">
		<h3>Use these instructions to build your program</h3>
		<ul id="instructions">
			<li class="draggable ui-state-default motor-speed">
				<? if($grade > 1) { ?>
				Set the motor speed to
				<input type="number" min="10" max="180" value="60">
				degrees per second (from 10 to 180)
				<? } else { ?>
				<span class="slider-label">SET SPEED</span> <input type="range" min="10" max="180">
				<? } ?>
			</li>
			<li class="draggable ui-state-default motor-angle">
				<? if($grade > 1) { ?>
				Move the motor to angle
				<input type="number" min="0" max="180" value="0">
				(from 0 to 180)
				<? } else { ?>
				<span class="slider-label">SET ANGLE</span> <input type="range" min="0" max="180">
				<? } ?>
			</li>
			<li class="draggable ui-state-default motor-delay">
				<? if($grade > 1) { ?>
				Pause for
				<input type="number" min="10" max="10000" value="100">
				milliseconds (from 10 to 10000)
				<? } else { ?>
				<span class="slider-label">PAUSE</span> <input type="range" min="0" max="1000">
				<? } ?>
			</li>
		</ul>
		</div>

		<div id="program-wrapper" class="wrapper">
		<h3>
			Build your program here
			<button type="button" id="run-button" onclick="runProgram()">Run It!</button>
			<span id="ready-light">Ready</span>
		</h3>
		<ul id="program">
		</ul>
		</div>
	</body>
</html>
