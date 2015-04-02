var getRequest, grabData, connectServer, next, updateFields;

// Sending AJAX
getRequest = function (num, req, callback) {
  $.ajax({
    url : req,
    type : "GET",
    dataType : "json",
    success: function (data) {
      if(typeof callback === 'function') {
        callback(data);
      } else {
        return;
      }
    }
  });
};

grabData = function () {
  var answer, num, req;
  answer = $('#answer').val();
  num = parseInt($("#current").text());
  return {
    answer: answer,
    num: num
  };
};

updateFields = function (data, isNext) {
  var num = data.questionNumber;
  if(isNext) {
    num += 1;
  }
  $("#current").text(num);
  $(".test__question .number").text(num);
  $(".test__question .question").text(data.question);
  $("#answer").val("");
};

connectServer = function (next, isInit) {
  var data = grabData();
  // Generating of request
  req = "scripts/testing.php?number=" + data.num + "&answer=" + data.answer.toLowerCase();
  if(!isInit) {
    getRequest(data.num, req, next);
  }
};

next = function(data) {
  if(data.secret) {
    updateFields(data);
    $('.signup__button').attr('href', data.text);
    $("#secret").html("<p><strong>Секретный код:</strong> " + data.secret + "</p><p>Перейти к регистрации: <a href='#signup'>Регистрация</a></p>");
    $("#secret").show(400);
  } else {
    if(data.questionNumber) {
      num = data.questionNumber;
      if((num + 1) <= 5) {
        updateFields(data, true);
      }
    } else {
      updateFields(data);
      $('.signup__button').attr('href', data.text);
      $("#failure").html("<p>К сожалению вы не ответили на все вопросы правильно, поэтому бонуса не будет. Перейти к регистрации: <a href='#signup'>Регистрация</a></p>");
      $('#failure').show(400);
    }
  }
};

$("#next").on("click", function () {
  if($('answer').val() !== "") {
    connectServer(next, false);
  }
});

// Smooth scrolling
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});

connectServer(updateFields, true);