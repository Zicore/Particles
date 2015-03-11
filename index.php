<!DOCTYPE HTML>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>Particles &#92;&#111;&#47;</title>
		
		<link rel="stylesheet" type="text/css" href="style/bootstrap.min.css" >
		<link rel="stylesheet" type="text/css" href="style/style.css">
		
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css">	
		
		<script type="text/javascript" src="scripts/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
		
		<script type="text/javascript" src="scripts/TS_vector2-GCopt.js"></script>
		<script type="text/javascript" src="scripts/Gravity.js"></script>
		<script type="text/javascript" src="scripts/Force.js"></script>
		<script type="text/javascript" src="scripts/Particle.js"></script>
		<script type="text/javascript" src="scripts/HeavyParticle.js"></script>
		<script type="text/javascript" src="scripts/stats.js"></script>
		<script type="text/javascript" src="scripts/game.js"></script>
		
	</head>
	<body>
	
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="collapse">
		  <div class="container-fluid">
			<ul class="nav navbar-nav">
				<li><span id="stats1"></span></li>
				<li><span id="stats2"></span></li>
			</ul>
			<div class="navbar-form navbar-right">
				<ul class="nav navbar-nav">
					
					<li>
						<div class="btn btn-ttc" id="label-grid">Grid</div>
					</li>
					<li>
						<div class="btn btn-ttc" id="label-stop">Stop</div>
					</li>
					<li>
						<div class="btn btn-ttc" id="label-gravity">Gravity off</div>
						<div id="slider-gravity"></div>
					</li>
					<li>
						<div class="label" id="label-mouse">Mouse</div>
						<div id="slider-mouse" class="slider-width"></div>
					</li>
					<li>
						<div class="label" id="label-friction">Friction</div>
						<div id="slider-friction" class="slider-width"></div>
					</li>
					<li>
						<div class="label" id="label-heavy-particle">Heavy Particle</div>
						<div id="slider-heavy-particle" class="slider-width"></div>
					</li>
					<li>
						<div class="label" id="label-distance-factor">Distance factor</div>
						<div id="slider-distance-factor" class="slider-width"></div>
					</li>
					<li>
						<div class="btn btn-ttc" id="label-add500">+1000</div>
					</li>
					<li>
						<div class="btn btn-ttc" id="label-reset">Clear</div>
					</li>
					<li>
						<div class="btn btn-ttc" id="label-hide">Hide</div>
					</li>
				</ul>										
			</div>			
		  </div>
		  <div class="label">Backspace=Stop+Grid, Enter=Grid, S=Stop, R=Reset, E=Explosion, P=+1000, H=Add Heavy Particle, G=Remove Heavy Particles, X=eXplosion, T=Toggle menu</div>
		</div>	
		<canvas id="viewport"></canvas>
		<script type="text/javascript">
		
		function update(){
			var gravityOn = Game.gravity.active ? "on" : "off";
			$( "#label-gravity" ).text("Gravity "+gravityOn+" ("+Game.gravity.f+")");
			$( "#label-mouse" ).text("Mouse ("+Game.force.f+")");
			$( "#label-friction" ).text("Friction ("+Game.friction+")");
			$( "#label-add500" ).text("+1000 ("+Game.entities.length+")");
			$( "#label-heavy-particle" ).text("Heavy Particle ("+Game.heavyParticleForce+")");
			$( "#label-distance-factor" ).text("Distance factor ("+Game.force.df+")");
		}
		
		$(function() {
		
			update();
		
			$( "#slider-gravity" ).slider({
				range: "min",
				min: 0.01,
				max: 0.5,
				value: 0.05,
				step: 0.01,
				slide: function( event, ui ) {
					Game.gravity.f = ui.value;
					update();
				}
			});
			
			$( "#slider-mouse" ).slider({
				range: "min",
				min: 0.01,
				max: 1.5,
				value: 0.1,
				step: 0.01,
				slide: function( event, ui ) {
					Game.force.f = ui.value;
					update();
				}
			});
			
			$( "#slider-friction" ).slider({
				range: "min",
				min: 0.9,
				max: 1,
				value: 0.982,
				step: 0.001,
				slide: function( event, ui ) {
					Game.friction = ui.value;
					update();
				}
			});
			
			$( "#slider-heavy-particle" ).slider({
				range: "min",
				min: 0.01,
				max: 0.8,
				value: 0.12,
				step: 0.01,
				slide: function( event, ui ) {
					Game.heavyParticleForce = ui.value;
					update();
				}
			});
			
			$( "#slider-distance-factor" ).slider({
				range: "min",
				min: 0.000,
				max: 1,
				value: 0.0,
				step: 0.001,
				slide: function( event, ui ) {
					Game.updateDistanceFactor(ui.value);
					update();
				}
			});
					
			$( "#label-gravity" ).click(function() {
				var enabled = Game.gravity.toggle();
				update();
			});	
			
			$( "#label-add500" ).click(function() {
				Game.addParticle(1000);
				update();
			});	
			
			$( "#label-grid" ).click(function() {
				Game.grid();
				update();
			});	
			
			$( "#label-stop" ).click(function() {
				Game.stop();
				update();
			});	
			
			$( "#label-reset" ).click(function() {
				Game.reset();
				update();
			});	
			
			$( "#label-hide" ).click(function() {
				$( "#collapse" ).toggle();
			});
			
			$(window).resize(function () {
				Game.resize();
			});
			
			$(document.body).on('keydown', function(e) {
				switch (e.which) {
					case 8: // Backspace
						e.preventDefault();
						Game.stop();
						Game.grid();
						break;
					case 13:						
						Game.grid(); 
						break;
					case 69:						
						Game.explosion();
						break;
					case 71:						
						Game.resetHeavyParticles();
						break;
					case 72:						
						Game.addHeavyParticle();
						break;
					case 80:						
						Game.addParticle(1000);
						update();
						break;	
					case 82:						
						Game.reset(); 
						break;
					case 83:						
						Game.stop(); 
						break;	
					case 84:						
						$("#collapse").toggle();
						break;	
					case 88:						
						Game.explosion2();
						break;					
				}
			});
		});
		
		var stats1 = document.getElementById("stats1");		
		var renderStats = new Stats();
		stats1.appendChild(renderStats.domElement);

		var stats2 = document.getElementById("stats2");	
		var updateStats = new Stats();
		stats2.appendChild(updateStats.domElement);

				
		
		Game.initialize();
		var canvas = document.getElementById("viewport");
		Game.addParticle(5000);		
		Game.grid();
      
		Game.run = (function() {
		  var loops = 0, skipTicks = 1000 / Game.fps,
			  maxFrameSkip = 15,
			  nextGameTick = (new Date).getTime(),
			  lastGameTick;

		  return function() {
			loops = 0;

			while ((new Date).getTime() > nextGameTick) {
			  Game.update();
			  updateStats.update();
			  nextGameTick += skipTicks;
			  loops++;
			}

			if (!loops) {
			  Game.draw((nextGameTick - (new Date).getTime()) / skipTicks);
			} else {
			  Game.draw(0);
			}
			renderStats.update();
		  };
		})();
      
      (function() {
        var onEachFrame;
        if (window.webkitRequestAnimationFrame) {
          onEachFrame = function(cb) {
            var _cb = function() { cb(); webkitRequestAnimationFrame(_cb); }
            _cb();
          };
        } else if (window.mozRequestAnimationFrame) {
          onEachFrame = function(cb) {
            var _cb = function() { cb(); mozRequestAnimationFrame(_cb); }
            _cb();
          };
        } else {
          onEachFrame = function(cb) {
            setInterval(cb, 1000 / 60);
          }
        }
        
        window.onEachFrame = onEachFrame;
      })();

      window.onEachFrame(Game.run);
	  

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-48379237-1', 'zicore.de');
		ga('set', 'anonymizeIp', true);
		ga('send', 'pageview');
		</script>
	</body>
</html>