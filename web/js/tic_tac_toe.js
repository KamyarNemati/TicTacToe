/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

function getSize(callback) {
    var size = $('#size').val();
    callback(size);
}
function callGame(input, callback) {
    $.ajax({
        url: '/api/game/TicTacToe',
        type: 'POST',
        data: input,
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
            callback(data);
        },
        complete: function (jqXHR, textStatus) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });
}
function processData(data, size) {
    var grid = jQuery.parseJSON(data);
    $('#json').val(data);
    $('#board').empty();
    content = '';
    var id = 0;
    for (var i = 0; i < size; ++i) {
        content += '<tr>';
        for (var j = 0; j < size; ++j) {
            ++id;
            content += '<td>' + '<button class="myBtn btn btn-success btn-lg" id=' + id + '>' + grid[i][j] + '</button>' + '</td>';
        }
        content += '</tr>';
    }
    $('#board').append(content);
}
$(document).on('click', '#init', function () {
    getSize(function (size) {
        var input = {
            size: size,
            json: null,
            play: null
        };
        callGame(input, function (obj) {
            processData(obj.data, size);
            if (obj.stat != 0) {
                alert(obj.msg);
            }
        });
    });
});
$(document).on('click', '.myBtn', function (e) {
    getSize(function (size) {
        var json = $('#json').val();
        var play = e.target.id;
        var input = {
            size: size,
            json: json,
            play: play
        };
        callGame(input, function (obj) {
            processData(obj.data, size);
            if (obj.stat != 0) {
                alert(obj.msg);
            }
        });
    });
});