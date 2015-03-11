
// Creates a force on a specific location
function Gravity(force){
	this.f = force;
	this.active = false;
}

Gravity.prototype.apply = function(game,p){
	if(this.active){
		p.vel.y += this.f * p.mass;
	}
}

Gravity.prototype.enable = function(){
	this.active = true;
}

Gravity.prototype.disable = function(){
	this.active = false;
}

Gravity.prototype.toggle = function(){
	this.active = !this.active;
	return this.active;
}