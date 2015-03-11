function Mouse(){
	this.x = 0;
	this.y = 0;
	this.isMouseLeftDown = false;
}

var Game = {};

Game.fps = 40;
Game.mouse = new Mouse();
Game.force =  new Force(20,50, 0.17);
Game.antiforce =  new Force(20,50, -6);

Game.initialize = function() {
	this.gravity = new Gravity(0.04);
	this.friction = 0.9982;
	this.heavyParticleForce = 0.2;
	this.antiforce.df = 0.05;
	
	this.entities = [];
	this.heavyParticles = [];
	this.canvas = document.getElementById("viewport");
	
	Game.resize();
	
	this.canvas.onmousemove = Game.onMouseMove;
	this.canvas.onmousedown = Game.onMouseDown;
	this.canvas.onmouseup = Game.onMouseUp;
	
	this.g = this.canvas.getContext("2d");
};


Game.resize = function(interpolation) {
	this.canvas.width = document.body.clientWidth; //document.width is obsolete
	this.canvas.height = document.body.clientHeight; //document.height is obsolete
	this.canvas.width = window.innerWidth;
	this.canvas.height = window.innerHeight;
};

Game.draw = function(interpolation) {
	//this.g.fillStyle = "#000000";
	this.g.clearRect(0,0,canvas.width,canvas.height);
	
	this.g.fillStyle = "#04B8f8";
	this.g.beginPath();
	for (var i=0; i < this.entities.length; i++) {
		this.entities[i].draw(this.g,interpolation);
	}
	
	this.g.closePath();
	this.g.fill();
	
	this.g.fillStyle = "#ff0808";
	this.g.beginPath();
	for (var j=0;j < this.heavyParticles.length;j++){
		var h = this.heavyParticles[j];
		h.draw(this.g,interpolation);
	}
	this.g.closePath();
	this.g.fill();
};

Game.update = function() {
  for (var i=0; i < this.entities.length; i++) {
	Game.force.apply(this,this.entities[i]);
	this.gravity.apply(this,this.entities[i]);
	
	for (var j=0;j < this.heavyParticles.length;j++){
		var h = this.heavyParticles[j];
		h.force.apply(this,this.entities[i]);
	}
	
	this.entities[i].update(this);
  }
};

Game.addParticle = function(p) {
  Game.entities.push(p);
};

Game.addHeavyParticle = function() {
	if(this.heavyParticles.length < 20){
		var h = new HeavyParticle(this.mouse.x,this.mouse.y);
		h.force.f = this.heavyParticleForce;
		Game.heavyParticles.push(h);
	}
};

Game.addParticle = function(count) {
	while(count--) this.entities.push(new Particle(0,0));
};

Game.reset = function() {
	this.entities.length = 0;
};

Game.resetHeavyParticles = function() {
	this.heavyParticles.length = 0;
};

Game.grid = function() {
	if(this.entities.length <= 0)
		return;
	var x = 0;
	var y = 0;
	var p = this.canvas.width*this.canvas.height/this.entities.length;
	var step = Math.sqrt(p);
	for(var i = 0; i < this.entities.length; i++){
		x+=step;
		if(x > canvas.width){
				y+=step;
				x=0;
		}
		var p = this.entities[i];
		p.v.x = x;
		p.v.y = y;
	}
};

Game.stop = function() {	
	for(var i = 0; i < this.entities.length; i++){		
		var p = this.entities[i];
		p.vel.x = 0;
		p.vel.y = 0;
	}
};

function rand(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}

Game.explosion = function(radius) {	
	if(this.entities.length <= 0)
		return;
	
	for(var i = 0; i < this.entities.length; i++){		
		var p = this.entities[i];
		
		var winkel = (4 * Math.PI / this.entities.length);
		var min = 410;
		var max = 450;
		var r = rand(rand(10, min), rand(min, max)) * 0.01 / 2;

		var x = Math.cos(i * winkel) * r;
		var y = Math.sin(i * winkel) * r;

		var delta = new Vec2(this.mouse.x + x,this.mouse.y + y);
		var speed = new Vec2(x,y);

		p.v.x = delta.x;
		p.v.y = delta.y;
		p.vel.x = speed.x;
		p.vel.y = speed.y;
	}
};

Game.explosion2 = function(radius) {	
	if(this.entities.length <= 0)
		return;
	
	for(var i = 0; i < this.entities.length; i++){			
		var p = this.entities[i];
		Game.antiforce.enable();
		Game.antiforce.apply(this,p);
		Game.antiforce.disable();
	}
};

Game.updateDistanceFactor = function(factor) {	
	this.force.df = factor;
		
	for(var i = 0; i < this.heavyParticles.length; i++){			
		var p = this.heavyParticles[i];
		p.force.df = this.force.df;
	}
};

Game.onMouseMove = function(e){
	var mouseX, mouseY;

	if(e.offsetX) {
		mouseX = e.offsetX;
		mouseY = e.offsetY;
	}
	else if(e.layerX) {
		mouseX = e.layerX;
		mouseY = e.layerY;
	}

	Game.mouse.x = mouseX;
	Game.mouse.y = mouseY;	

	Game.force.move(Game.mouse.x,Game.mouse.y);
	Game.antiforce.move(Game.mouse.x,Game.mouse.y);
	if(Game.mouse.isMouseLeftDown){		
		Game.force.enable();
	}
};

Game.onMouseDown = function(e){
	Game.mouse.isMouseLeftDown = true;
			
	if(Game.mouse.isMouseLeftDown){
		Game.force.move(Game.mouse.x,Game.mouse.y);
		Game.force.enable();
	}
};

Game.onMouseUp = function(e){
	Game.mouse.isMouseLeftDown = false;
	Game.force.disable(); 
};