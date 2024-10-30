$ = jQuery;
$(document).ready(function() {
  var domSearch = new autoComplete({
    selector: '.dataautocompletion',
    minChars: 2,
    source: function(term, response){
        $.ajax( {
    url: "https://api.mixcloud.com/search",
    dataType: "jsonp",
    data: {
      type: 'user',
      q: term
    },
    success: function( data ) {
        $(".informationChannel").html();
        response( data.data );
    }
  });

    },
    renderItem: function (item, search){
        search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&amp;');
        var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
        return '<div class="autocomplete-suggestion" data-username="'+item.username+'"><img width="20px" src="'+item.pictures.medium+'">'+(item.name).replace(re, "<b>$1</b>")+'</div>';
    },
    onSelect: function(e, term, item){
        username = item.getAttribute('data-username');
        $(".dataautocompletion").val(username);
        informationChannel(username);
    }
});

function informationChannel(username) {
    $.ajax( {
        url: "https://api.mixcloud.com/"+username,
        dataType: "jsonp",
        success: function( data ) {
            var json = data ;
            console.log(data);
            $(".informationChannel").html('<img width="20px" src="'+json.pictures.medium+'">'+json.cloudcast_count+" playlists found");
        }
      });
}
});