@extends('hotel_auth.main_layout')

<!-- 標題 -->
@section('title', $Title)
@section('sub_fun', 'room_set')
<!-- 飯店名稱 -->
@section('hotel_name', $Hotel->name)

@section('content')

    <div class="modal fade" id="okAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">套用下列範例名稱</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              123
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">OK！</button>
          </div>
        </div>
      </div>
    </div>

<div>
<ul style="list-style: none;">
  <li class="count_type" style="width: 70px;"><a href="room_set">全部房型</a></li>
  <li class="count_type" style="width: 70px;"><a href="room_set?t=1">背包客</a></li>
  <li class="count_type"><a href="room_set?p=1">1人</a></li>
  <li class="count_type"><a href="room_set?p=2">2人</a></li>
  <li class="count_type"><a href="room_set?p=3">3人</a></li>
  <li class="count_type"><a href="room_set?p=4">4人</a></li>
  <li class="count_type"><a href="room_set?p=5">5人</a></li>
  <li class="count_type"><a href="room_set?p=6">6人</a></li>
  <li class="count_type"><a href="room_set?p=7">7人</a></li>
  <li class="count_type"><a href="room_set?p=8">8人</a></li>
  <li class="count_type"><a href="room_set?p=9">9人</a></li>
  <li class="count_type"><a href="room_set?p=10">10人</a></li>
  <li class="count_type"><a href="room_set?p=11">11人</a></li>
  <li class="count_type"><a href="room_set?p=12">12人</a></li>
  <li class="count_type" style="width: 70px;"><a href="room_set?p=13">13人以上</a></li>
  <li class="count_type"><a href="room_set?t=2">包棟</a></li>
  <li class="count_type"><a href="room_set?t=3">包層</a></li>
  <li class="count_type"><a href="room_set?t=4">露營</a></li>
</ul>
<div style="width:100px;float:right;top: -45px;position:relative;"><a href="./room_set/add">新增房型</a></div>
</div>
@foreach($RoomSet as $key => $room)
<div>
  <table width="100%" class="main_table" border="0" cellspacing="0" cellpadding="0" style="">
    <tbody>
    <tr>
      <td width="15%" height="150" rowspan="3" bgcolor="#FFFFFF" ><div align="center"><img src="/photos/room/250/{{$RoomPhotosArray[$key]}}" width="188" height="137"></div></td>
      <td bgcolor="#FBEEC7" align="center" style="width: 300px;">房型名稱</td>
      <td bgcolor="#FBEEC7" align="center" style="width: 500px;" >床型</td>
      <td bgcolor="#FBEEC7" align="center" >住宿人數</td>
      <td bgcolor="#FBEEC7" align="center" >總間數</td>
      <td bgcolor="#FBEEC7" align="center" >開放間數</td>
      <td bgcolor="#FBEEC7" align="center" >面積(坪)</td>
      <td rowspan="2" valign="top" align="center">
        <a href="room_set/{{$room->nokey}}">編輯</a>
        <br>
        <br>
        <a href="room_del/{{$room->nokey}}" onclick="return confirm('確定要刪除嗎？')">刪除</a>
      </td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF" style="color:red"><div align="left">{{$room->name}}</div></td>
      <td bgcolor="#FFFFFF" >
        @foreach($RoomBeds[$key] as $rkey => $bed)
          {{$bed}} <br>
        @endforeach
      </td>
      <td bgcolor="#FFFFFF" align="center" >{{$room->min_people}}</td>
      <td bgcolor="#FFFFFF" align="center" >{{$room->room_count}}</td>
      <td bgcolor="#FFFFFF" align="center" >{{$room->room_open_count}}</td>
      <td bgcolor="#FFFFFF" align="center" >{{$room->room_area}}</td>
      </tr>
    <tr>
      <td colspan="8" bgcolor="#FFFFFF"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tbody><tr>
          <td style="border: 0px;"><span style="color:#269547;font-weight: bold;">房間特色：</span>{{$room->room_feature}}</td>
        </tr>
        <tr>
          <td style="border: 0px;"><span style="color:#269547;font-weight: bold;">房內設施：</span>
            @foreach($DeviceArray[$key] as $kk => $device)
                @foreach($Device as $k => $dv)
                  @if($dv->nokey ==$device)
                    {{$dv->service_name}}｜
                  @endif
                @endforeach
            @endforeach
          </td>
        </tr>
      </tbody></table></td>
      </tr>
  </tbody></table>
  <p></p>
</div>
@endforeach
<!-- main -->

@endsection

@section('instyle')
.count_type{
  display: inline-block;
  width:50px;
  text-align:center;
}
.main_table {
    width:98%;
    margin: auto;
    border: 1px solid #999; border-collapse: collapse;
}
.main_table > tr, td {
    border: 1px solid #999;
}

@endsection

<!-- js獨立區塊腳本 -->
@section('custom_script')
//$('.checkbox :checked').parent().addClass("service_select");



@endsection

<!-- jQuery ready 狀態內閉包內插 -->
@section('custom_ready_script')

@endsection