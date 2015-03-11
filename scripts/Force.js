
// Creates a force on a specific location
function Force(x,y, force){
	this.v = new Vec2(x,y);
	this.f = force;
	this.active = false;
	this.df = 0;
}

Force.prototype.apply = function(game,p){
	if(this.active){		
		var delta = new Vec2(this.v.x,this.v.y);
		var v1 = new Vec2(this.v.x,this.v.y);
		var distanceFactor = this.df;
		
		vMath.subV(delta,p.v);
		
		var distance = 1.0;
		if(distanceFactor < 0.01){
			distanceFactor = 0; // no calculation when factor is near 0
		}else{
			distance = vMath.dist(v1,p.v) * distanceFactor;
			
			if(distance < 0.1 && distance > -0.1)
				distance = 0.1; // distance should not be near 0
		}
			
		vMath.normalize(delta);		
		vMath.mulS(delta,this.f / distance);	
		vMath.addV(p.vel,delta);
	}
}

Force.prototype.move = function(x,y){
	this.v.x = x;
	this.v.y = y;
}

Force.prototype.enable = function(){
	this.active = true;
}

Force.prototype.disable = function(){
	this.active = false;
}