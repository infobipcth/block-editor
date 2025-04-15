# Block Editor

This package is a Work In Progress. It aims to seperate the Javascript frontend from [Laraberg](https://github.com/VanOns/laraberg) so it can be maintained seperately, and maybe serve as a starting point for other backend implementations.

## Usage

To use the editor simply create a input or textarea element and use it to initalize it like this:

```js
import { initializeEditor } from '@van-ons/block-editor';

const element = document.querySelector('#content');
initializeEditor(element);
```

## Inside a React component

You can create an editor component like this:

```tsx
import React, { useEffect, useRef } from 'react';
import { router } from '@inertiajs/react';
import { initializeEditor } from '@van-ons/block-editor';
import { trigger} from '@/events/events';

export default function Editor({ id, content }: {id: number, content: string}) {
    const editorInitializedRef = useRef(false);
    const textareaRef = useRef<HTMLTextAreaElement>(null);

    useEffect(() => {
        if (!editorInitializedRef.current) {
            initializeEditor(textareaRef.current);

            editorInitializedRef.current = true;
            trigger('Laraberg:initialized', {});
        }
    }, []);

    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault();

        let content;
        if (textareaRef.current) {
            content = textareaRef.current.textContent;
        }

        router.post('/posts', { id: id, content});
    };

    return (
        <form onSubmit={handleSubmit}>
            <textarea
                id="content"
                name="content"
                title="content"
                ref={textareaRef}
                hidden
                defaultValue={content}
                />
            <button
                type="submit"
                className="focus:shadow-outline mt-10 cursor-pointer rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none"
            >
                Save
            </button>
        </form>
    )
}
```

Here, we are using events to trigger a syntatic `Laraberg:initialized` event in order to be able to use it in other places in our app. This way we can ensure we aren't trying to use the editor when it's not ready.

## Getting GB package versions:

1. Clone the gutenberg package:
    ```bash
    git clone git@github.com:WordPress/gutenberg.git
    ```
2. Run the `versions.sh` script
    ```bash
   bash versions.sh
    ```

You will get a list of packages like:

```bash
"@wordpress/api-fetch": "~7.22.0",
"@wordpress/base-styles": "~5.22.0",
"@wordpress/block-editor": "~14.17.0",
"@wordpress/block-library": "~9.22.0",
"@wordpress/blocks": "~14.11.0",
"@wordpress/components": "~29.8.0",
"@wordpress/data": "~10.22.0",
"@wordpress/element": "~6.22.0",
"@wordpress/format-library": "~5.22.0",
"@wordpress/hooks": "~4.22.0",
"@wordpress/keyboard-shortcuts": "~5.22.0",
"@wordpress/server-side-render": "~5.22.0",
```

Replace `~` with `^`, and paste in `package.json`.
