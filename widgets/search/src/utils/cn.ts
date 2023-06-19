import classnames from 'classnames';

export default function cn(...classNames: string[]): string {
    return classnames(classNames)
}