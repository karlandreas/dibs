var spriteSheet = new Image();
var canvas = document.getElementById('game-canvas');
var context = canvas.getContext('2d');

document.getElementsByTagName('body')[0].onload = function() {
	document.getElementsByTagName('h1')[0].innerHTML = "OK";
	spriteSheet.src = 'spriteSheet.png';
}

spriteSheet.onload = function(event) {
	console.log("Time: " + event.timeStamp);
	
	context.save();
	
	context.drawImage(spriteSheet, 0,0, 250,250, 0,0, 250,250);
	
	context.restore();
	
	context.save();
	context.clearRect(0, 0, canvas.width, canvas.height);
	
	context.drawImage(spriteSheet, 500, 250, 250,250, 0,0, 250,250);
	
	context.restore();
}