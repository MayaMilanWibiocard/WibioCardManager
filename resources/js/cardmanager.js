    const d = document;
    var _readers;
    var _cardType;
    async function ListReaders()
    {
        _readers = await navigator.webcard.readers();
        d.querySelector("#h_mess").innerHTML = "Extension loaded";
        d.querySelector("#t_mess").innerHTML = _readers.length + " readers detected";
        d.querySelector("#b_mess").innerHTML = "Put your card on the reader";
    }

    navigator.webcard.cardInserted = function (reader) {
        d.querySelector("#h_mess").classList.add("bg-success");
        d.querySelector("#h_mess").classList.remove("bg-danger");
        d.querySelector("#h_mess").innerHTML = "Card detected";
        d.querySelector("#t_mess").innerHTML = "Card inserted in " + reader.name;
        d.querySelector("#b_mess").innerHTML = "ATR detected, wait while recognize card type"??'_';
        if (reader.atr == '3B8A8001534C434F5320543D434C0D'){
            fetchPartialPage('/authenticaC', '#card_div');
            _cardType = 'C';
        }else{
            fetchPartialPage('/authenticaF', '#card_div');
            _cardType = 'F';
            countDownClock(30, 'seconds');
        }
    }

    navigator.webcard.cardRemoved = function (reader) {
        d.querySelector("#h_mess").classList.add("bg-danger");
        d.querySelector("#h_mess").classList.remove("bg-success");
        d.querySelector("#h_mess").innerHTML = "Card removed";
        d.querySelector("#t_mess").innerHTML = "Card removed from " + reader.name;
        d.querySelector("#b_mess").innerHTML = "Put your card on the reader to continue working";
        d.querySelector('#card_div').innerHTML = "";
    }

    function hex2a(hexx) {
        var hex = hexx.toString();//force conversion
        var str = '';
        for (var i = 0; i < hex.length; i += 2)
            str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
        return str;
    }

    function fetchPartialPage(url, dest)
    {
        fetch(url).then(function (response) {
            return response.text();
        }).then(function (html) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            d.querySelector(dest).innerHTML = doc.body.innerHTML;
            d.querySelector("#b_mess").innerHTML = "Card data loaded";
        }).catch(function (err) {
            console.warn('Something went wrong.', err);
        }
        );
    }

    const countDownClock = (number = 100, format = 'seconds') => {
        let countdown;
        timer(number);
        function timer(seconds) {
            const now = Date.now();
            const then = now + seconds * 1000;
            countdown = setInterval(() => {
                const secondsLeft = Math.round((then - Date.now()) / 1000);
                if(secondsLeft <= 0) {
                    clearInterval(countdown);
                    d.querySelector('#opt').innerHTML = '';
                    return;
                };
                displayTimeLeft(secondsLeft);
            },1000);
        }
        function displayTimeLeft(seconds) {
            d.querySelector('.seconds').textContent = seconds % 60 < 10 ? `0${seconds % 60}` : seconds % 60;
        }
    }

    ListReaders();
