<template>
	<re-modal
		:id="id"
		title="Adicionar marca d'água"
		:show="show"
		v-on:close="close"
		v-on:ok="finish"
		v-on:modal-shown="initialise"
	>
		<div class="d-flex justify-content-center">
			<div :id="idBlockCanvas" class="block-of-canvas">
				<div class="block-of-canvas-loading" v-if="loading">
					<ph-circle-notch :size="80" weight="thin">
						<animateTransform
							attributeName="transform"
							attributeType="XML"
							type="rotate"
							dur="0.5s"
							from="0 0 0"
							to="360 0 0"
							repeatCount="indefinite"
						/>
					</ph-circle-notch>
					<p>Carregando...</p>
				</div>

				<canvas :id="`canvas-${id}`"></canvas>
			</div>
		</div>
	</re-modal>
</template>

<script>
import ReModal from "@/components/Modals/Modal";
import { PhCircleNotch } from "phosphor-vue";
import { Canvas, FabricImage } from "fabric";

export default {
	name: "ModalImageWatermark",
	props: {
		id: {
			type: String,
			default: null,
		},
		show: {
			type: Boolean,
			default: false,
		},
		image: {
			type: Object,
			default: null,
		},
		imageWatermark: {
			type: Object,
			default: null,
		},
	},
	components: {
		ReModal,
		PhCircleNotch,
	},
	data() {
		return {
			canvas: null,
			idBlockCanvas: null,
			loading: true,
		};
	},
	watch: {},
	beforeMount() {
		this.idBlockCanvas = this.id
			? `${this.id}-block-of-canvas`
			: `block-of-canvas-${this._uid}`;
	},
	mounted() {},
	methods: {
		initialise() {
			if (this.show && this.image && this.imageWatermark) {
				this.initCanvas();
				this.addWatermarkAndResize();
			}
		},
		removeImageOfCanvas(img) {
			this.canvas.remove(img);
		},
		close() {
			this.canvas.getObjects("image").forEach(this.removeImageOfCanvas);
			this.loading = true;
			this.$emit("close");
		},
		finish() {
			const dataURL = this.canvas.toDataURL("image/png");
			this.$emit("ok", { id: this.image.id, image: dataURL });
		},
		initCanvas() {
			const div = document.getElementById(this.idBlockCanvas);
			const larguraDiv = div.offsetWidth;
			const alturaDiv = div.offsetHeight;
			if (!this.canvas) {
				this.canvas = new Canvas(`canvas-${this.id}`, {
					width: larguraDiv,
					height: alturaDiv,
				});
			} else {
				this.canvas.setWidth(larguraDiv);
				this.canvas.setHeight(alturaDiv);
			}
		},
		addWatermarkAndResize() {
			const context = this;

			FabricImage.fromURL(this.image.url).then(function (mainImg) {
				const proporcaoCanvas = context.canvas.width / context.canvas.height;
				const proporcaoImagem = mainImg.width / mainImg.height;

				let novaLargura, novaAltura;

				if (proporcaoCanvas > proporcaoImagem) {
					novaAltura = context.canvas.height;
					novaLargura =
						mainImg.width * (context.canvas.height / mainImg.height);
				} else {
					novaLargura = context.canvas.width;
					novaAltura = mainImg.height * (context.canvas.width / mainImg.width);
				}
				context.canvas.setWidth(novaLargura);
				context.canvas.setHeight(novaAltura);

				mainImg.set({
					selectable: false,
					hasControls: false,
					evented: false,
					left: 0,
					top: 0,
				});
				mainImg.scaleToWidth(novaLargura);
				mainImg.scaleToHeight(novaAltura);

				FabricImage.fromURL(context.imageWatermark.url).then(function (
					watermark
				) {
					watermark.set({
						opacity: 0.8,
						selectable: true,
						evented: true,
						left: mainImg.left + 20,
						top: mainImg.top + 20,
					});
					const scaleFactor = 0.2;
					const watermarkWidth = watermark.width;
					const watermarkHeight = watermark.height;
					watermark.scaleToWidth(context.canvas.width * scaleFactor);
					watermark.scaleToHeight(
						context.canvas.width *
							scaleFactor *
							(watermarkHeight / watermarkWidth)
					);
					context.canvas.add(mainImg);
					context.canvas.add(watermark);
					context.canvas.renderAll();

					setTimeout(() => {
						context.loading = false;
					}, 2000);
				});
			});
		},
	},
};
</script>

<style lang="scss" scoped>
.block-of-canvas {
	max-height: 100%;
	height: 300px;
	width: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
	&-loading {
		position: absolute;
	}
}
</style>
