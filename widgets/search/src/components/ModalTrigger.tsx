import { Trigger } from "@radix-ui/react-dialog";
import { forwardRef } from "react";
import { FiMessageSquare } from "react-icons/fi";
import cn from "../utils/cn";

interface Props extends React.ComponentPropsWithoutRef<typeof Trigger> {
  isOpen?: boolean;
}

const ModalTrigger = forwardRef<React.ElementRef<typeof Trigger>, Props>(
  ({ isOpen, ...props }, _ref) => {
    return (
      <Trigger
        {...props}
        ref={_ref}
        className={cn(
          "ch-p-2 ch-text-xl ch-font-manrope ch-text-primary !ch-z-[1000000] ch-group ch-font-semibold ch-gap-1 ch-fixed ch-bottom-2 ch-transition ch-right-4 ch-flex ch-items-center ch-justify-center",
          isOpen ? "ch-flex-col" : "ch-flex-row"
        )}
      >
        <FiMessageSquare
          size={30}
          className="group-hover:ch-scale-110 ch-transition-transform"
        />
        <span className="ch-px-1.5 ch-py-1 ch-text-sm ch-rounded-md ch-bg-bg ch-text-accent">
          /
        </span>
      </Trigger>
    );
  }
);
ModalTrigger.displayName = "Trigger";
export default ModalTrigger;
