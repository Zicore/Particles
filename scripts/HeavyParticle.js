
function HeavyParticle(x,y){
	this.v = new Vec2(x,y); // Vector
	this.vel = new Vec2(0,0); // Velocity
	this.radius = 3;
	this.mass = 1;
	this.force = new Force(x,y,0.5);
	this.force.enable();
}

HeavyParticle.prototype.update = function(game,interpolation){	
	if (this.v.x > game.canvas.width || this.v.x < 0) {
		this.vel.x *= -1;
	}

	if (this.v.y > game.canvas.height) {
		if(Math.abs(this.vel.y) > 0.01){
			this.vel.y *= -0.7;
		}
	}
	
	if(this.v.y < 0){
		this.vel.y *= -1;
	}
	
	vMath.mulS(this.vel,game.friction);
	vMath.addV(this.v,this.vel);
}

HeavyParticle.prototype.draw = function(g){
	var radByTwo = this.radius / 2.0;
	g.rect(this.v.x - radByTwo,this.v.y - radByTwo,this.radius,this.radius);
}