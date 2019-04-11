
--automatically generated file | fMT-Export (c)YT v0.03-215Jul17 

data:extend({

	--YI-Basics
	{ type="item-group", name="yuoki", icon="__Yuoki__/graphics/icons/yuoki-ind-icon.png", inventory_order="y", order="yi-a" },
	--YI-Energy
	{ type="item-group", name="yuoki-energy", icon="__Yuoki__/graphics/icons/yuoki-energy.png", inventory_order="y", order="yi-b" },
	--YI-Atomics
	{ type="item-group", name="yuoki-atomics", icon="__Yuoki__/graphics/icons/yuoki-atomics-icon.png", inventory_order="y", order="yi-c" },
	--YI-Refinery & Fluids 
	{ type="item-group", name="yuoki_liquids", icon="__Yuoki__/graphics/icons/yuoki-liquids.png", inventory_order="y", order="yi-d" },

	--alte Rezepte
	{ type="item-subgroup", name="y-fluid", group="yuoki_liquids", order="1-0" },
	--fame
	{ type="item-subgroup", name="y-stargate-f", group="yuoki", order="2-6" },
	{ type="item-subgroup", name="_name", group="yuoki", order="_order" },

	{ type="recipe-category", name="advanced-crafting" }, --
	{ type="recipe-category", name="chemistry" }, --
	{ type="recipe-category", name="crafting" }, --
	{ type="recipe-category", name="crafting-with-fluid" }, --
	{ type="recipe-category", name="oil-processing" }, --
	{ type="recipe-category", name="smelting" }, --all furnace stuff
	{ type="recipe-category", name="y-crushing-recipe" }, --
	{ type="recipe-category", name="yuoki-alien-recipe" }, --infuser ?
	{ type="recipe-category", name="yuoki-formpress-recipe" }, --
	{ type="recipe-category", name="yuoki-stargate-recipe" }, --stargate-trades

})