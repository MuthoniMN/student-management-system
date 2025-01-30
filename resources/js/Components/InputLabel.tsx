import { ReactNode } from "react";

type TLabelProps = {
    value?: string,
    className?: string,
    children?: ReactNode,
    htmlFor?: string
}

export default function InputLabel({
    value,
    className = '',
    children,
    htmlFor,
    ...props
}: TLabelProps ) {
    return (
        <label
            {...props}
            className={
                `block text-sm font-medium text-gray-700 ` +
                className
            }
            htmlFor={htmlFor}
        >
            {value ? value : children}
        </label>
    );
}
