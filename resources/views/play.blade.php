<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/gciplayer.css?v=1.01">
</head>
<style>
    .video-container {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        height: 700px;
        background-color: #000000;
    }

    .videoDiv {
        height: 30%;
        width: 30%;
        border: 1px solid white;
    }
</style>

<body>
<div style="width:704px;margin:0 auto;margin-bottom: 8px;">
    播放地址: <input type="text" name="playUrl" id="playUrl"
                     value="{{$url}}" style="width:500px">
</div>

<div style="width:704px;margin:0 auto;margin-bottom: 8px;">
    <button type="button" onclick="createPlayer()">CreatePlayer</button>
    <button type="button" onclick="play()">Play</button>
    <button type="button" onclick="capture()">Capture</button>
    <button type="button" onclick="playClose()">Close</button>
    <button type="button" onclick="destroy()">Destroy</button>
    <button type="button" onclick="toggleFullScreen()">ToggleFullScreen</button>
    <button type="button" onclick="toggle()">toggle</button>
    <button type="button" onclick="currentTime()">currentTime</button>

</div>
<div class="black-bg" style="width:804px;height:576px;margin:0 auto;background-color:#000000;">
    <div id="player" style="height:100%;width:100%;margin:0 auto;background-color:#000000;">
    </div>
</div>
<!-- <img id="preview" src="" alt="1"> -->
<script src="/js/gciplay.js"></script>
<script>
    console.log(gciplayer.isSupported());
    var openapi = 'http://10.94.2.23:8088/gci/api/';
    const clientID = 'test',
        clientSecret = 'test'
    let accessToken = ''

    var simNo = '220000104513';

    var player = null;

    // 1.获取token
    // getAccessToken((token) => {
    //     accessToken = token
    // })

    function createPlayer() {
        if (player) return;
        var options = {
            stretch: true,
            controls: false,
            showFlow: true,
            renderType: 2,
            wasmFile: '{{ asset('wasm/libvideoflv.wasm') }}', //wasm目录下，自行放到服务器
        }
        var element = document.getElementById('player');
        player = gciplayer.createPlayer(element, options);

        player.on('error', function (error) {
            console.log(error);
        })
        player.on('success', function () {
            console.log('成功')
        })
        player.on('flow', function (flowsize) {
            // console.log('流量：' + flowsize)
        })
        player.on('close', () => {
            console.log('close')
        })
    }

    var playSession = '';
    function play() {
        if (!player) return;
        var realPlay = openapi + 'video/RealPlay';
        var playback = openapi + 'video/PlayBack';

        // var data = {
        //     simNo: simNo,
        //     channel: 1,
        //     bitType: 2,
        // }
        // fetchPost(realPlay, data)
        //     .then((res) => {
        //         console.log(res);
        //         if (res.data.status == 1) {
        //             playSession = res.data.sessionId;
        //             var url = res.data.websocketUrl + '?sessionid=' + res.data.sessionId;
        //             player.play(url);
        //         }
        //         else {
        //             alert('不在线')
        //         }
        //     })
        var url = document.getElementById('playUrl').value;
        player.play(url);
    }
    function playClose() {
        // console.log('close')
        player.close(() => {
            play();
            console.log('damn')
        });
    }
    function destroy() {
        player.destroy();
        player = null;
    }
    function toggleFullScreen() {
        player.toggleFullScreen();
    }
    function capture() {
        player.capture(false, (file) => {
            console.log(file)
            // var fileReader = new FileReader();
            // fileReader.readAsDataURL(file);
            // fileReader.onload = (evt) => {
            //     let img = document.getElementById('preview');
            //     img.src = fileReader.result;
            // }
        });
    }
    function toggle() {
        console.log(player.hasAudio)
        // stretch = !stretch;
        // player.setStretch(stretch);
    }
    function currentTime() {
        console.log(player.currentTime);
    }

    function getAccessToken(callback) {
        let paramsUrl = `?grant_type=client_credentials&scope=video&client_id=${clientID}&client_secret=${clientSecret}`
        fetch(openapi + 'token' + paramsUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then((data) => {
                // console.log(data)
                // access_token = data.access_token
                callback(data.access_token)
            })
    }

    function fetchGet(url, params) {
        let urlParams = '?'
        Object.entries(params).forEach(([key, value]) => {
            urlParams += key + '=' + encodeURI(value) + '&'
        })
        urlParams = urlParams.substring(0, urlParams.lastIndexOf('&'))
        let getUrl = url + urlParams;
        return fetch(getUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'bearer ' + accessToken
            },
        }).then(response => response.json())
    }
    function fetchPost(url, postData) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'bearer ' + accessToken
            },
            body: JSON.stringify(postData)
        }).then(response => response.json())
    }
    createPlayer();
    play();
</script>
</body>

</html>
