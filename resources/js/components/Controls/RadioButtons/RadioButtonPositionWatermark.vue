<template>
	<div class="position-grid">
		<label v-for="pos in positions" :key="pos.value" class="position-item">
			<input
				type="radio"
				name="position"
				:value="pos.value"
				:checked="pos.value === value"
				@change="changeValue"
			/>
			<span class="box" :class="{ selected: pos.value === value }"></span>
		</label>
	</div>
</template>

<script>
export default {
	name: "RadioButtonPositionWatermark",
	props: {
		value: {
			type: String,
			default: null,
		},
	},
	watch: {
		value(newValue) {
			console.log("NEWVALUE", newValue);
		},
	},
	data() {
		return {
			positions: [
				{ value: "top-left" },
				{ value: "top" },
				{ value: "top-right" },
				{ value: "left" },
				{ value: "center" },
				{ value: "right" },
				{ value: "bottom-left" },
				{ value: "bottom" },
				{ value: "bottom-right" },
			],
		};
	},
	methods: {
		changeValue(evt) {
			this.$emit("input", evt.target.value);
		},
	},
};
</script>

<style lang="scss" scoped>
@import "bootstrap/scss/functions";
@import "bootstrap/scss/_variables";
.position-grid {
	display: grid;
	grid-template-columns: repeat(3, 20px);
	grid-gap: 10px;
	justify-content: flex-start;
}

.position-item {
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
}

.position-item .box {
	width: 20px;
	height: 20px;
	background-color: #ccc;
	border: 2px solid #999;
	border-radius: 4px;
	transition: background-color 0.3s, border-color 0.3s;
}

.position-item .box.selected {
	background-color: $primary;
	border-color: darken($primary, 0.5);
}

.position-item input {
	display: none;
}
</style>
