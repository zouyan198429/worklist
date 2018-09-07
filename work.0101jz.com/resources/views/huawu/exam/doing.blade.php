@extends('layouts.huawu')

@push('headscripts')
{{--  本页单独使用 --}}
@endpush

@section('content')

	<div id="crumb"><i class="fa fa-reorder fa-fw" aria-hidden="true"></i> 在线考试</div>

	<div class="mm" id="examinbox">
		<div class="mmhead" >
			进度：15/50  <br />
			剩余时间：65分钟
		</div>
		<div class="content">
			<h1>您被分到一个单位当领导，想提出一些解决工作中烦难问题的好方法。这时候，您第一件要做的是什么呢？</h1>
			<ul>
				<li>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" /> 起草一个议事日程，以便充分利用和大家在一起讨论的时间
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" /> 给人们一定的时间相互了解
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" /> 让每一个人说出如何解决问题的想法
					</label>
				</li>
				<li>
					<label>
						<input type="radio" name="radiobutton" value="radiobutton" /> 采用一种创造性地发表意见的形式，鼓励每一个人说出此时进入他脑子里的任何想法，而不管该想法有多疯狂！
					</label>
				</li>
			</ul>

		</div>

		<div class="mmfoot tc">
			<a href="{{ url('huawu/exam/win') }}" class="btn" > 下一题 > </a>
		</div>

	</div>
@endsection


@push('footscripts')
@endpush

@push('footlast')
@endpush