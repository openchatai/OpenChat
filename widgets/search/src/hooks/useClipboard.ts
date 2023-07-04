import { useState } from 'react'


// eslint-disable-next-line @typescript-eslint/no-explicit-any
export default function useCopyToClipboard(text: string|any): [boolean, () => void] {
    const [isCopied, setIsCopied] = useState(false)

    const copy = async () => {
        if (!navigator?.clipboard) {
            console.warn('Clipboard not supported')
        }

        try {
            await navigator.clipboard.writeText(text)
            setIsCopied(true)
            setTimeout(() => {
                setIsCopied(false)
            }, 3 * 1000)


        } catch (error) {
            console.warn('Copy failed', error)
            setIsCopied(false)
        }
    }

    return [isCopied, copy]
}