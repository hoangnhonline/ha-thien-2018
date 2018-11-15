<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mail</title>
</head>
<body>

<div style="padding:20px;width: 80%;text-align: left;font-size: 17px">
  <h3>Chúc mừng khách hàng <strong style="color: red">{{ $cus->full_name }}</strong></h3>

  <p>Bạn đã đạt tổng doanh thu : <strong style="color: red"> {{ number_format( $total ) }} vnđ</strong> - tương ứng với Hạng thành viên <strong style="color:red">{{ $hang }}</strong> </p>

  <p>Kể từ ngày <strong style="color: red">{{ date('d/m/Y', strtotime($date_apply)) }}</strong>, bạn sẽ đc chiết khấu <strong style="color: red">{{ $ck }}%</strong> trên tổng đơn hàng.</p>

  <p>Cảm ơn bạn đã luôn ủng hộ <strong>muanhanhgiatot.vn</strong></p>

</div>

</body>
</html>