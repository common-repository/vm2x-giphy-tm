/**
 * Created by admin on 2022-05-05.
 */

var control1 ;
var doc ;
function open_windows(openwindow, control) {
    var obj = window.open(openwindow, 'GIPHY GIF Searcher', "dialogWidth=740px;dialogHeight=600px;center=yes;help=no;resizable=no;status=no;toolbar=no;menubar=no");
    //obj.opener = "" ;
    obj.onload = function () {
        obj.document.title = "GIPHY GIF Searcher" ;
    }

    //if (!str)
    //    return;
    control1 = control ;
    //control.setContent( '<img src="' + str[0] + '" alt="orignal" width="30" height="30" />' );

    //document.getElementById(control).value = str[0];
    //document.getElementById(control2).value = str[1];
}

function chooseImage(url,vwidth,vheight)
{

    //alert("closeing!!!!" + ret);
    ed = tinymce.activeEditor;
    ed.selection.setContent('<img src="' + url + '" alt="orignal" width="'+ vwidth +'" height="'+ vheight +'" />' );
    tb_remove();
    //control1.setContent( '<img src="' + url + '" alt="orignal" width="'+ vwidth +'" height="'+ vheight +'" />' );
    //delGrey(doc);

}

/*
function chooseImage(url,vwidth,vheight)
{

    window.opener.window.close_windows(url,vwidth,vheight);
    window.close();


}*/

//window.opener = "";  //Giphy Image Finder
function loadMore(n)
{

    //console.log(jQuery("#g_limit").val());
    //console.log(n * jQuery("#g_limit").val() + 1);
    jQuery("#g_index").val(n * jQuery("#g_limit").val() + 1);
    jQuery('#gifsearch_Btn').trigger("click");

}

jQuery(document).ready(function() {
    jQuery('#gifsearch_Btn').click(function(e) {
        jQuery("#loading").html("<img src='"+ jQuery("#loading_2_path").val() +"assets/theme/Loading_2.gif' width='50px' height='50px'>");
        jQuery("#gifsearch_Btn").attr("disabled", true);
        jQuery.post(vigi_my_ajax_obj.ajax_url, {         //POST request
            _ajax_nonce: vigi_my_ajax_obj.nonce,     //nonce
            action: "vigi_my_search_gifs",            //action
            giphy_keywords: jQuery("#keywords").val(),                  //data
            giphy_type:jQuery("#gType").val(),
            g_index:jQuery("#g_index").val()

        }, function(data) {                    //callback
            jQuery("#loading").html("");
            jQuery("#gifsearch_Btn").attr("disabled", false);
            var jsonData = data ;  //JSON.parse(data);

            // user is logged in successfully in the back-end
            // let's redirect
            if (jsonData.success == "1")
            {
                var htmlstr ="";
                var jdata =  jsonData.data ;
                if(jdata instanceof Array)
                {
                    for(var i in jdata) {
                        var gif_url = jdata[i]["images"]["original"]["url"];
                        var width = jdata[i]["images"]["original"]["width"];
                        var height = jdata[i]["images"]["original"]["height"];

                        var htmlstr = htmlstr + '<a href="#" onclick="chooseImage(\'' + gif_url + '\','+ width +','+ height +')"><img src="' + gif_url + '" alt="orignal" width="' + width + '" height="' + height + '" /></a>';
                        //alert(htmlstr);
                        //console.log(htmlstr);
                    }
                }
                else{
                    var gif_url = jdata["images"]["original"]["url"];
                    var width = jdata["images"]["original"]["width"];
                    var height = jdata["images"]["original"]["height"];

                    var htmlstr = htmlstr + '<a href="#" onclick="chooseImage(\'' + gif_url + '\','+ width +','+ height +')"><img src="' + gif_url + '" alt="orignal" width="' + width + '" height="' + height + '" /></a>';


                }

                jQuery("#g_limit").val(jsonData.limit);

                htmlstr = '<p>' + htmlstr +'</p>';
                jQuery("#ImageMain").html(htmlstr);


            }
            else
            {
                alert('Invalid Keywords!');
            }
        });
    });
});


document.getElementById("ImageMain").addEventListener("scroll", handleScroll2);
ncount_scroll = 1 ;
function handleScroll2() {
    innerHeight = jQuery("#ImageMain").height();
    scrollTop = document.getElementById("ImageMain").scrollTop;
    scrollHeight = jQuery("#ImageMain").get(0).scrollHeight ;


    if(scrollTop + innerHeight >= scrollHeight){    // 距离顶部+当前高度 >=文档总高度，即代表滑动到底部
        loadMore(ncount_scroll);
        ncount_scroll ++ ;
    }


}




