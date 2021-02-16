//---------------TABS-----------------//
$('[data-toggle="tab"],[id="logout"]').tooltip({
    trigger: 'hover',
    placement: 'top',
    animate: true,
    delay: 1000,
    container: 'body'
});

var hash = location.hash.replace(/^#/, '');
if (hash) {
    $('.nav-tabs a[href="#' + hash + '"]').tab('show');
}
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})
//-------------------------------------//


//------------HIDE-SELECT--------------//
let select = document.getElementById("telegram_notifications_period[type]");
select.onchange=function(){
    if(select.value === "week"){
        document.getElementById("telegram_notifications_period[day]").style.display="block";
    }else{
        document.getElementById("telegram_notifications_period[day]").style.display="none";
    }
}
//--------------------------------------//


//-----------TAGIFY-TEXTAREA------------//
let tags = [
    { value: 1, text: 'Artist Name', key: 'artist_name' },
    { value: 2, text: 'Artist Name (link)', key: 'artist_name_link' },
    { value: 3, text: 'Release Name', key: 'release_name' },
    { value: 4, text: 'Release Name (link)', key: 'release_name_link' },
    { value: 5, text: 'Release Date', key: "release_date" },
    { value: 6, text: 'Release Artists List', key: "release_artists_list" },
    { value: 7, text: 'Release Type', key: "release_type" },
    { value: 8, text: 'Artist URI', key: 'artist_uri' },
    { value: 9, text: 'Artist URI (link)', key: 'artist_uri_link' },
    { value: 10, text: 'Release URI', key: 'release_uri' },
    { value: 11, text: 'Release URI (link)', key: 'release_uri_link' },
    { value: 12, text: 'Artist ID', key: 'artist_id' },
    { value: 13, text: 'Artist ID (link)', key: 'artist_id_link' },
    { value: 14, text: 'Release ID', key: 'release_id' },
    { value: 15, text: 'Release ID (link)', key: 'release_id_link' },
    { value: 16, text: 'Artist URL', key: 'artist_url' },
    { value: 17, text: 'Release URL', key: "release_url" }
];
let input = document.querySelector('[id="telegram_notifications_format"]'),
    tagify = new Tagify(input, {
        mode: 'mix',
        mixTagsInterpolator: ['[[[[', ']]]]'],
        keepInvalidTags: false,
        enforceWhitelist: true,
        pattern: /@/,
        tagTextProp: 'text',
        whitelist: tags,
        transformTag: transformTag,
        dropdown : {
            enabled: 1,
            position: 'text',
            mapValueTo: 'text',
            highlightFirst: true,
            caseSensitive: false,
            maxItems:20,
        },
    });
tagify.on('input', function(e){
    let prefix = e.detail.prefix;
    if(prefix && prefix === '@'){
        tagify.settings.whitelist = tags;
        tagify.dropdown.show.call(tagify, e.detail.value);
    }
})

function getRandomColor(){
    function rand(min, max) {
        return min + Math.random() * (max - min);
    }
    var h = rand(1, 360)|0,
        s = rand(40, 70)|0,
        l = rand(65, 72)|0;
    return 'hsl(' + h + ',' + s + '%,' + l + '%)';
}

function transformTag( tagData ){
    tagData.style = "--tag-bg:" + getRandomColor();
}
//--------------------------------------//
