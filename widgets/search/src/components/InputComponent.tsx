import { ComponentProps, useState } from "react";
import TextareaAutosize from "react-autosize-textarea";
import { IoSend } from "react-icons/io5";
import { useRef, useEffect } from "react";
import { AiOutlineArrowUp } from "react-icons/ai";
import { instance } from "../utils/axios_instance";

type SuggestionType = { text: string; url: string };

function InputComponent({
  ready,
  ...props
}: ComponentProps<typeof TextareaAutosize> & { ready?: boolean }) {
  const input_ref = useRef<HTMLTextAreaElement>(null);
  const [suggestions, setSuggestions] = useState<Array<SuggestionType> | []>(
    []
  );
  // get suggestions
  useEffect(() => {
    try {
      instance
        .get<Array<SuggestionType>>("/58fc397f-00d2-47f4-95a7-e9bc93de98ab")
        .then((d) => d.data)
        .then((d) => setSuggestions(d));
    } catch (error) {
      setSuggestions([]);
    }
  }, []);

  useEffect(() => {
    if (input_ref) {
      input_ref.current?.focus();
    }
  }, []);

  return (
    <div className="ch-w-full ch-p-2 ch-flex ch-flex-col ch-items-start ch-gap-2">
      <div className="ch-flex ch-items-center ch-gap-1 ch-justify-start ch-flex-wrap">
        {suggestions.map((s, i) => (
          <button
            key={i}
            className="ch-text-sm ch-font-medium ch-px-2 ch-py-1 ch-bg-fg ch-rounded-md ch-text-accent text-focus-in"
            style={{ animationDelay: "0.1s" }}
          >
            {s.text}
          </button>
        ))}
      </div>
      <div className="ch-bg-bg_light ch-rounded-xl ch-p-1.5 ch-w-full">
        <div className="ch-flex ch-gap-1 w-full ch-items-center ch-flex-row">
          <div className="ch-flex-1 p-1">
            <TextareaAutosize
              {...props}
              maxRows={10}
              className="ch-w-full ch-leading-7 placeholder:ch-leading-loose ch-text-accent placeholder:ch-text-sm ch-bg-transparent ch-text-lg ch-font-normal ch-p-1 ch-align-middle ch-min-h-full ch-outline-none"
              type="text"
              placeholder="ask any question?"
              ref={input_ref}
            />
          </div>
          <button
            disabled={!ready}
            className="ch-p-2 ch-rounded-xl ch-hidden ch-text-center ch-text-primary ch-text-2xl"
          >
            <IoSend />
          </button>
          <button
            disabled={ready}
            className="ch-z-10 ch-text-sm sm:ch-text-base ch-m-0 ch-p-0 ch-text-accent ch-bg-primary ch-gap-1 ch-items-center ch-rounded-lg ch-flex ch-py-0.5 ch-px-2 disabled:ch-opacity-50 disabled:ch-cursor-not-allowed ch-transition-all"
          >
            <span>Ask</span>
            <AiOutlineArrowUp />
          </button>
        </div>
      </div>
    </div>
  );
}

export default InputComponent;
