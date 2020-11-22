<template>
    <div class="radial-progress" :data-progress="progress">
        <div class="circle">
            <div class="mask full">
                <div class="fill"></div>
            </div>
            <div class="mask half">
                <div class="fill"></div>
                <div class="fill fix"></div>
            </div>
            <div class="shadow"></div>
        </div>
        <div class="inset">
            <div class="percentage"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProgressCircle",
    props: ['progress']
}
</script>

<style lang="less" scoped>
@import url(http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic);

.radial-progress {
	@circle-size: 70px;
	@circle-background: #1b1b1b;
	@circle-color: #e74c3c;
	@inset-size: 63px;
	@inset-color: #282828;
	@transition-length: 1s;
	@shadow: 6px 6px 10px rgba(0,0,0,0.2);
	@percentage-color: #fff;
	@percentage-font-size: 16px;
    @percentage-text-width: 57px;
    @font-family: 'Josefin Sans';
    @font-weight: 400;

	width:  @circle-size;
	height: @circle-size;

	background-color: @circle-background;
	border-radius: 50%;
	.circle {
		.mask, .fill, .shadow {
			width:    @circle-size;
			height:   @circle-size;
			position: absolute;
			border-radius: 50%;
		}
		.shadow {
			box-shadow: @shadow inset;
		}
		.mask, .fill {
            backface-visibility: hidden;
			-webkit-backface-visibility: hidden;
			transition: -webkit-transform @transition-length;
			transition: -ms-transform @transition-length;
			transition: transform @transition-length;
			border-radius: 50%;
		}
		.mask {
			clip: rect(0px, @circle-size, @circle-size, @circle-size/2);
			.fill {
				clip: rect(0px, @circle-size/2, @circle-size, 0px);
				background-color: @circle-color;
			}
		}
	}
	.inset {
		width:       @inset-size;
		height:      @inset-size;
		position:    absolute;
		margin-left: (@circle-size - @inset-size)/2;
		margin-top:  (@circle-size - @inset-size)/2;

		background-color: @inset-color;
		border-radius: 50%;
		box-shadow: @shadow;
		.percentage {
			width:       @percentage-text-width;
			position:    absolute;
			top:         (@inset-size - @percentage-font-size) / 2;
			left:        (@inset-size - @percentage-text-width) / 2;

			line-height: 1;
			text-align:  center;

			font-family: @font-family, "Helvetica Neue", Helvetica, Arial, sans-serif;
			color:       @percentage-color;
			font-weight: @font-weight;
			font-size:   @percentage-font-size;
		}
	}

	@i: 0;
	@increment: 180deg / 100;
	.loop (@i) when (@i <= 100) {
		&[data-progress="@{i}"] {
			.circle {
				.mask.full, .fill {
					-webkit-transform: rotate(@increment * @i);
					-ms-transform: rotate(@increment * @i);
					transform: rotate(@increment * @i);
				}	
				.fill.fix {
					-webkit-transform: rotate(@increment * @i * 2);
					-ms-transform: rotate(@increment * @i * 2);
					transform: rotate(@increment * @i * 2);
				}
			}
			.inset .percentage:before {
				content: "@{i}%"
			}
		}
		.loop(@i + 1);
	}
	.loop(@i);
}
</style>