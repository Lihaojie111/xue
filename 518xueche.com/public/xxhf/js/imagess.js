var delParent;
var number=0;
var remove_id = "";
var up_id = "";
var delete_id;

var defaults = {
	fileType : ["jpg","png","bmp","jpeg"],		// 上传文件的类型
	fileSize : 1024 * 1024 * 10					// 上传文件的大小 10M
};

var imgnumber = 1;
$(function(){
	init();
	if (document.getElementById("imgnumber1")) {
		imgnumber = document.getElementById("imgnumber1").value;
	}
	var leng = $('#total1 section.up-section').length;
	if (leng >= imgnumber) {
		$("#total1 .z_file").hide();
	}
	$(".z_photo").delegate(".close-upimg", "click", function() {
		$(".works-mask").show();
		delParent = $(this).parent();
	});
	$(".wsdel-no").click(function() {
		$(".works-mask").hide();
	});
});


function upFile(url, obj) {
	number++;
	var img = event.target.files[0];
	if(!validateUp(img)){
		return;
	}
	
	var imgContainer = obj.parents(".z_photo");
	var addContainer = obj.parent();
	var $section = $("<section id='" + number + "' class='up-section fl loading' >");
	addContainer.before($section);

	var reader = new FileReader();
	reader.readAsDataURL(img);
	reader.onload = function(e) {
		$.post(url, {
			img : e.target.result
		}, function(ret) {
			if (ret.img !== '') {
				$section.attr("path",ret.img);
			}
		}, 'json');
	}
	var $span = $("<span class='up-span'></span>");
	$span.appendTo($section);
	var $img0 = $("<img class='close-upimg' onclick='delete_click($(this))' />");
	$img0.attr("src", "/public/xxhf/images/a7.png").appendTo($section);
	var $img = $("<img class='up-img up-opcity' name='shopimg" + number + "'>");
	$img.attr("src", window.URL.createObjectURL(img));
	$img.appendTo($section);
	var $p = $("<p class='img-name-p'>");
	$p.appendTo($section);
	setTimeout(function() {
		$(".up-section").removeClass("loading");
		$(".up-img").removeClass("up-opcity");
	}, 300);
	var numUp = imgContainer.find(".up-section").length;
	if (numUp >= imgnumber) {
		obj.parent().hide();
	}
	obj.val("");
}

function changeFile(url) {
	var img = event.target.files[0];
	if(!validateUp(img)){
		return;
	}
	var reader = new FileReader();
	reader.readAsDataURL(img);
	reader.onload = function(e) {
		$.post(url, {
			img : e.target.result
		}, function(ret) {
			if (ret.img !== '' && up_id) {
				$("#" + up_id).attr('path', ret.img);
			}
		}, 'json');
	}
	var shopname = "";
	if (remove_id) {
		shopname = $("#" + remove_id + " .up-img").attr("name");
		$("#" + remove_id + " .up-img").remove();
		up_id = remove_id;
	}
	var $section = $("#" + remove_id);
	var $img = $("<img class='up-img up-opcity' name='" + shopname + "'>");
	$img.attr("src", window.URL.createObjectURL(img));
	$img.appendTo($section);

	setTimeout(function() {
		$(".up-section").removeClass("loading");
		$(".up-img").removeClass("up-opcity");
	}, 100);
	$(this).val("");
}

function delete_click(obj) {

	event.preventDefault();
	event.stopPropagation();
	$(".works-mask").show();
	delParent = obj.parent();
	delete_id = obj.attr("pic");
}

function deleteImg(obj) {

	obj.parent().parent().parent().hide();
	var flag = true;
	if(delete_id){
		$.ajax({
			type : "POST",
			url : "/guanli/goods/delimg",
			async : false,
			data : {
				id : delete_id
			},
			success : function(data) {
				if (data.code !== 1) {
					flag = false;
					alert("删除失败");
				}
			}
		});
	}
	if(flag){
      delParent.next().show();
		delParent.remove();
		
	}
}

function updateImg(obj) {
	event.preventDefault();
	event.stopPropagation();
	var change_click = obj.parent();
	remove_id = change_click.attr('id');
	change_click.parent().find("input[name='file_copy']").click();
}

function init() {
	var total_div = $("div[name='total_div']");
	for (var i = 0; i < total_div.length; i++) {
		$(total_div[i]).attr("id", "total_div" + (i + 1));
	}
	var total_input = $(".total_input");
	for (i = 0; i < total_input.length; i++) {
		$(total_input[i]).attr("name", "total" + (i + 1));
	}
	var file_input = $("input[name='file']");
	for (i = 0; i < file_input.length; i++) {
		$(file_input[i]).attr("id", "file" + (i + 1));
		$(file_input[i]).attr("class", "file" + (i + 1));
	}
	var file_copy_input = $("input[name='file_copy']");
	for (i = 0; i < file_copy_input.length; i++) {
		$(file_copy_input[i]).attr("id", "file_copy" + (i + 1));
		$(file_copy_input[i]).attr("class", "file_copy" + (i + 1));
	}
}

function validateUp(file) {
	var newStr = file.name.split("").reverse().join("");
	if (newStr.split(".")[0] != null) {
		var type = newStr.split(".")[0].split("").reverse().join("");
		if (jQuery.inArray(type, defaults.fileType) > -1) {
			if (file.size >= defaults.fileSize) {
				alert('您这个"' + file.name + '"文件大小过大');
				return false;
			}else{
				return true;
			}
		} else {
			alert('您这个"' + file.name + '"上传类型不符合');
			return false;
		}
	} else {
		alert('您这个"' + file.name + '"没有类型, 无法识别');
		return false;
	}
}

