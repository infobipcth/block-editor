import { getBlockTypes } from '@wordpress/blocks'
// @ts-ignore — no type declarations for this export
import { __experimentalGetCoreBlocks, registerCoreBlocks } from '@wordpress/block-library'

interface CoreBlock {
	name: string
	init: () => void
	metadata: Record<string, unknown>
	settings: Record<string, unknown>
}

/**
 * Register all supported core blocks that are not registered yet and are not disabled in the settings
 *
 * @param disabledCoreBlocks
 */
function registerBlocks(disabledCoreBlocks: string[] = []) {
	registerCoreBlocks(
		filterRegisteredBlocks(
			getCoreBlocks(disabledCoreBlocks)
		)
	)
}

/**
 * Remove blocks that are already registered from an array of blocks
 *
 * @param blocks
 */
function filterRegisteredBlocks(blocks: CoreBlock[]) {
	const registredBlockNames = getBlockTypes().map(b => b.name)
	return blocks.filter(b => !registredBlockNames.includes(b.name))
}

/**
 * Get all supported core blocks except for the ones disabled through settings
 *
 * @param disabledCoreBlocks
 */
export const getCoreBlocks = (disabledCoreBlocks: string[] = []): CoreBlock[] => {
	const allBlocks: CoreBlock[] = __experimentalGetCoreBlocks()
	return allBlocks.filter(b => !disabledCoreBlocks.includes(b.name))
}

export { registerBlocks }
