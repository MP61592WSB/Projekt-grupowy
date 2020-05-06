var time = 0;
$("document").ready(
    function(evt)
    {
        $("#shoutbox").submit(
            function()
            {
                $.post("odbierz_dane.php", {
                    "text": $("#text").val(),
                    "nick": $("#nick").val(),
                    "time": time,
                    "action": "post"
                },
                function(daneXML)
                {
                    addMessage(daneXML);
                });
                
                $("#text").val('');
                return false;
            }
        );
        updateMessage();
    }
);

function updateMessage()
{
    $.post("odbierz_dane.php", {"time": time},
        function(daneXML)
        {
            addMessage(daneXML);
        }
    );
    
    setTimeout(updateMessage, 2000);
}

function addMessage(daneXML)
{
    if($("status", daneXML).text() == "0") return;
    
    var wiadomosci = $("wiadomosc", daneXML);
    
    time = $("timestamp", daneXML).text();
    wiadomosci.each(
        function(i)
        {
            var nick = $("nick", this);
            var tresc = $("tresc", this);
            var czas = $("czas", this);
            
            $("#messages").prepend("["+czas.text()+"] [<b>"+nick.text()+"</b>] "+tresc.text()+"</br>");
        }
    );
}