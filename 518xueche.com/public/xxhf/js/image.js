var delParent;
var defaults = {
	fileType : [ "jpg", "png", "bmp", "jpeg" ],
	fileSize : 1024 * 1024 * 10
};
$(document).ready(function() {
	$(".file").change(function() {
		var idFile = $(this).attr("id");
		var file = document.getElementById(idFile);
		var imgContainer = $(this).parents(".z_photo"); // 存放图片的父亲元素
		var fileList = file.files; // 获取的图片文件
		var imgArr = [];
		// 遍历得到的图片文件
		var numUp = imgContainer.find(".up-section").length;
		var totalNum = numUp + fileList.length; // 总的数量
		if (fileList.length > 1 || totalNum > 1) {
			window.top.dialog("上传图片数目不可以超过1个，请重新选择", 1, 3); // 一次选择上传超过1个
			// 或者是已经上传和这次上传的到的总数也不可以超过1个
		} else if (numUp < 1) {
			fileList = validateUp(fileList);
			for (var i = 0; i < fileList.length; i++) {
				var imgUrl = window.URL.createObjectURL(fileList[i]);
				imgArr.push(imgUrl);
				var $section = $("<section class='up-section fl loading'>");
				imgContainer.prepend($section);
				var $span = $("<span class='up-span'>");
				$span.appendTo($section);

				var $img0 = $("<img class='close-upimg'>").on("click", function(event) {
							event.preventDefault();
							event.stopPropagation();
							$(".works-mask").show();
							delParent = $(this).parent();
						});
				$img0.attr("src",
						"/public/xxhf/images/a7.png")
						.appendTo($section);
				var $img = $("<img class='up-img up-opcity'>");
				$img.attr("src", imgArr[i]);
				$img.appendTo($section);
				var $p = $("<p class='img-name-p'>");
				$p.html(fileList[i].name).appendTo($section);
				var $input = $("<input value='' type='hidden'>");
				$input.appendTo($section);
				var $input2 = $("<input value='' type='hidden'/>");
				$input2.appendTo($section);
			}
		}
		setTimeout(function() {
			$(".up-section").removeClass("loading");
			$(".up-img").removeClass("up-opcity");
		}, 410);
		numUp = imgContainer.find(".up-section").length;
		if (numUp >= 1) {
			$(this).parent().hide();
		}
	});
	
	$(".z_photo").delegate(".close-upimg", "click", function() {
		$(".works-mask").show();
		delParent = $(this).parent();
	});

	$(".wsdel-ok").click(function() {
    
		$(".works-mask").hide();
		var numUp = delParent.siblings().length;
		if (numUp < 6) {
			delParent.parent().find(".z_file").show();
		}
      delParent.parent().find(".file").val('');
      console.log(delParent.find(".file"));
		delParent.remove();
        
	});

	$(".wsdel-no").click(function() {
		$(".works-mask").hide();
	});
});

function validateUp(files) {
	var arrFiles = [];// 替换的文件数组
	for (var i = 0, file; file = files[i]; i++) {
		var fileType = file.name.split("").reverse().join("");
		if (addFile(fileType, file)) {
			arrFiles.push(file);
		}
	}
	return arrFiles;
}

function addFile(fileType, file) {
	if (fileType.split(".")[0] != null) {
		var type = fileType.split(".")[0].split("").reverse().join("");
		if (jQuery.inArray(type, defaults.fileType) > -1) {
			if (file.size >= defaults.fileSize) {
				alert('您这个"' + file.name + '"文件大小过大,请选择10M以下图片', 1, 3);
				return false;
			} else {
				return true;
			}
		} else {
			window.top.dialog('您这个"' + file.name + '"上传类型不符合', 1, 3);
			return false;
		}
	} else {
		window.top.dialog('您这个"' + file.name + '"没有类型, 无法识别', 1, 3);
		return false
	}
}

function setImage(id,image) {
	var imgContainer = $(id).parents(".z_photo");
	var $section = $("<section class='up-section fl loading'>");
	imgContainer.prepend($section);
	var $span = $("<span class='up-span'>");
	$span.appendTo($section);
	var $img = $("<img style='width: 190px;height: 190px;' class='up-img up-opcity'>");
	$img.attr("src", "data:image/gif;base64," + image);
	$img.appendTo($section);
	var $p = $("<p class='img-name-p'>");
	$p.html('000').appendTo($section);
	var $input = $("<input value='' type='hidden'>");
	$input.appendTo($section);
	var $input2 = $("<input value='' type='hidden'/>");
	$input2.appendTo($section);
	var $imgClose = $("<img class='close-upimg'>").on("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		$(".works-mask").show();
		delParent = $(this).parent();
	});
	$imgClose.attr("src", "/public/xxhf/images/a7.png").appendTo($section);
	setTimeout(function() {
		$(".up-section").removeClass("loading");
		$(".up-img").removeClass("up-opcity");
	}, 400);
	var numUp = imgContainer.find(".up-section").length;
	if (numUp >= 1) {
		$(id).parent().hide();
	}
}