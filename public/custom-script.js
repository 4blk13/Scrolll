function showModal(element, isVideo = 0) {
    if (isVideo) {
        var tag = $('<video src="'+ $(element).attr("src") +'" class="img-fluid imagepreview" playsinline autoplay muted loop></video>');
    }
    else {
        var tag = $('<img src="'+ $(element).attr("src").replace('_thumb', '') +'" class="img-fluid imagepreview">');
    }
    $('.modal-body').append(tag);
    $('.modal-title').html($(element).attr("data-image-title"));
    var modal = new bootstrap.Modal(document.getElementById("modal"));
    modal.show();
    $('.modal-body').one('click', function() {
        if(isVideo) {
            element.load();
        }
        modal.hide();
        $('.modal-body').empty();
        $('.modal-title').html('');
    });
}

function sendAjax(pageNumber) {
    $.ajax({
        type: 'POST',
        url:  'scriptHome.php/page/' + pageNumber,
        dataType: 'json',
        data: {json : JSON.stringify(categories)},
        cache: false,
        success: function(result) {
            $('#column-1').append(result[0]);
            $('#column-2').append(result[1]);
            $('#column-3').append(result[2]);
            lowerColumnHeight = attrLowerColumn();
            canInsert = 1;
        },
        error: function(result) {
            console.log(result);
            $('body').append(result.responseText);
        }
    });
}

function showResult(searchString) {
    if (searchString.length==0) {
        $("#livesearch").html('');
        $("#livesearch").css("border", "0px");
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'search-categoryHome.php/q/' + searchString,
        dataType: 'text',
        cache: false,
        success: function(result) {
            $("#livesearch").show();
            $("#livesearch").html(result);
            $("#livesearch").css("border", "1px solid #A5ACB2");
        },
        error: function() {
        }
    });
}

function attrLowerColumn() {
    var column1Height = 0;
    $('#column-1').children().each(function() {
        column1Height += $(this).outerHeight();;
    });

    var column2Height = 0;
    $('#column-2').children().each(function() {
        column2Height += $(this).outerHeight();
    });

    var column3Height = 0;
    $('#column-3').children().each(function() {
        column3Height += $(this).outerHeight();
    });
    return Math.min(column1Height, column2Height, column3Height);
}

function chooseCategories(thisItem, categorieID, categorieName) {
    if (!(categories.includes(categorieID))) {
        categories.push(categorieID);
        $('#column-1').html('');
        $('#column-2').html('');
        $('#column-3').html('');
        var button = $('<button type="button" class="btn btn-danger close" style="font-size:16px" onclick="removeCategories(this,' + categorieID + ')">' + categorieName + '   <i class="fa fa-close"></i></button>');
        $('.selected-categories').append(button);
        sendAjax(0);
        pageNumber = 1;
    }
    else {
        $('#livesearch').show();
        $(thisItem).tooltip({title:'Already added', trigger:'manual'});
        $(thisItem).tooltip('show');
        setTimeout(function(){$(thisItem).tooltip('hide');}, 2000);
    }
}

function removeCategories(thisItem, categorieID) {
    categories.splice(categories.indexOf(categorieID.toString()), 1);
    $(thisItem).fadeOut(function(){thisItem.remove();});
    $('#column-1').html('');
    $('#column-2').html('');
    $('#column-3').html('');
    sendAjax(0);
    pageNumber = 1;
}

var pageNumber = 1;

if (window.location.pathname === '/') {
    var canInsert = 1;
    var lowerColumnHeight;
    var categories = [];
    $(document).ready(function(){
        sendAjax(0);
        $(window).on('scroll', function(){
            if ((canInsert === 1) && ($(window).scrollTop() + $(window).height() >= lowerColumnHeight)){
                canInsert = 0;
                sendAjax(pageNumber);
                pageNumber++;
            }
        });
        $(document).click(function(event) { 
            var target = $(event.target);
            if(!target.is('input.form-control') && $('#livesearch').is(":visible")) {
                $('#livesearch').hide();
            }
        });

        $('#search-container').click(function(event) {
            var target = $(event.target);
            if (target[0].value != '' && $('#livesearch').is(":hidden")) {
                $('#livesearch').css("overflow-y", "scroll");
                $('#livesearch').show();
                event.stopPropagation();
            }
        });
    });
}

if ('/edit-file.php' === window.location.pathname) {
    $(document).ready(function(){
        $('#select-categories').selectize({
            plugins: ['remove_button'],
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            loadThrottle: null,
            highlight: false,
            placeholder: 'Search one or multiple categories',
            preload: true,
            render: {
                option: function(item, escape) {
                    return '<div class="test"><img src="Assets/'+ item.thumb +'" height=100 width=100 class="img-fluid"></img><span>' + item.name + '</span></div>';
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: 'search-category.php/q/' + encodeURIComponent(query),
                    type: 'POST',
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(JSON.parse(res));
                    }
                });
            }
        });
    });
}