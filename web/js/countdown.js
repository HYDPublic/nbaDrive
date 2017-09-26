$(document).ready(function() {

	var seconds = parseInt($('#seconds').text());
	console.log(seconds);
	console.log('lol test time beatch');


	// On recupere chaque elements de la date

	function addZero(number)
	{
		if( number < 10 )
			return '0'+number;
		return number;
	}


	if( $('#finished').text() == '1') {

		intervalHandle = setInterval(function(){
			seconds--;
			$('#seconds').text(addZero(seconds));

			// Si seconde = 0
			if( seconds == 0) {

				var minutes = parseInt($('#minutes').text());
						
				if( minutes == 0) {

					var hours = parseInt($('#hours').text());

					if( hours == 0){

						var days = parseInt($('#days').text());

						if( days == 0) {
							var months = $('#months').text();
							months--;
							$('#months').text(months);
							days = 31;
						}

						days--;
						$('#days').text(addZero(days));
						hours = 24;
					}

					hours--;
					$('#hours').text(addZero(hours));
					minutes = 60;

				}

				minutes--;
				$('#minutes').text(addZero(minutes));
				seconds = 60;
			}

		}, 1000);
	}

});
