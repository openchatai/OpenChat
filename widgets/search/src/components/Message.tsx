import { LuStars } from "react-icons/lu";
import * as ReactToggle from "@radix-ui/react-toggle-group";
import useCopyToClipboard from "../hooks/useClipboard";
import { BsFillClipboardFill, BsFillClipboard2CheckFill } from "react-icons/bs";
import { AiFillDislike, AiFillLike } from "react-icons/ai";
import { CiUser } from "react-icons/ci";
import { instance } from "../utils/axios_instance";
import Markdown from "./MarkDown.Hilight";
import { useEffect, useState } from "react";

function Source({
  children,
  ...props
}: React.AnchorHTMLAttributes<HTMLAnchorElement>) {
  return (
    <a
      {...props}
      className="ch-mr-2 ch-bg-fg ch-min-w-[64px] ch-w-fit ch-max-w-xs sm:ch-max-w-[275px] hover:ch-border-gray-100 ch-no-underline group ch-whitespace-nowrap ch-relative ch-m-0 ch-mb-2 ch-rounded-lg ch-border ch-border-gray-500 ch-border-opacity-20 ch-p-0  ch-py-1  ch-px-2 ch-text-xs ch-font-normal hover:ch-cursor-pointer focus:ch-z-10 focus:ch-ring-transparent  sm:ch-min-w-0 sm:ch-py-2 sm:ch-text-sm"
    >
      <div className="ch-flex ch-items-center ch-justify-start ch-w-full">
        <span className="ch-truncate ch-text-white">{children}</span>
      </div>
    </a>
  );
}

export default function Message({
  bot,
  firstMsg,
  children,
  messageId,
  sources,
  timeoutFinishCallback,
}: {
  bot?: boolean;
  firstMsg?: boolean;
  messageId?: string;
  content?: string;
  children: string;
  sources?: Array<{ source_url: string; source_text: string }> | [];
  timeoutFinishCallback?: () => void;
}) {
  const [isCopid, copy] = useCopyToClipboard(children);
  function rateMessage(isGood: boolean | null) {
    if (bot && !firstMsg) {
      const data = {
        message_id: messageId,
        is_good: isGood,
      };
      instance.post("/81345265-dbf5-4c7e-ae0b-ff325e324c0d", data);
    }
  }

  // the typewriter stuff
  const [displayText, setDisplayText] = useState("");
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isComplete, setIsComplete] = useState(false);

  useEffect(() => {
    if (bot) {
      if (currentIndex < children.length) {
        const timer = setInterval(() => {
          setDisplayText(children.substring(0, currentIndex + 1));
          setCurrentIndex((prevIndex) => prevIndex + 1);
        }, 0.00001);

        return () => {
          clearInterval(timer);
        };
      } else {
        setIsComplete(true);
        if (typeof timeoutFinishCallback === "function") {
          timeoutFinishCallback();
        }
      }
    }
  }, [children, currentIndex, timeoutFinishCallback,bot]);

  return (
    <div className="message sm:ch-py-4 sm:ch-px-2 ch-w-full ch-max-w-full">
      <div className="actual">
        <div className="ch-flex sm:ch-items-center ch-items-start ch-flex-col sm:ch-flex-row ch-justify-start ch-gap-1 sm:ch-gap-4 w-full">
          <div className="sm:ch-mb-auto">
            <div className="sm:ch-hidden ch-text-xs ch-font-medium ch-text-primary">
              {bot ? "Assistant" : "You"}
            </div>
            <div className="avatar ch-hidden sm:ch-inline-flex">
              {bot ? (
                <div className="ch-h-8 ch-bg-primary ch-w-8 ch-min-w-[32px] ch-flex-col ch-overflow-hidden ch-items-center ch-justify-center ch-rounded-lg ch-border ch-border-gray-500 ch-border-opacity-20 ch-flex sm:ch-h-8 sm:ch-w-8 sm:ch-min-w-[32px]">
                  <LuStars />
                </div>
              ) : (
                <div className="ch-h-8 ch-w-8 ch-min-w-[32px] ch-flex-col ch-overflow-hidden ch-items-center ch-justify-center ch-rounded-lg ch-border ch-border-gray-500 ch-border-opacity-20 ch-flex sm:ch-h-8 sm:ch-w-8 sm:ch-min-w-[32px]">
                  <CiUser />
                </div>
              )}
            </div>
          </div>
          <div className="ch-w-full ch-pb-3 sm:ch-py-0">
            <div className="msg ch-leading-6 ch-text-base ch-font-normal ch-w-full">
              <div className="ch-text-accent ch-text-[16px] [&>pre]:ch-my-4 [&>pre]:ch-max-w-[90%]">
                {bot ? <Markdown content={displayText} /> : <>{children}</>}
              </div>
            </div>

            {bot && !firstMsg && isComplete && (
              <div className="feedback ch-my-5 fade-in-bottom">
                <div className="ch-w-full ch-block ch-h-px ch-bg-bg_light ch-my-2" />
                <div className="ch-font-normal ch-text-sm">
                  was this response helpful?
                </div>
                <ReactToggle.Root
                  onValueChange={(v) => {
                    if (v) {
                      rateMessage(v === "yes" ? true : false);
                    } else {
                      rateMessage(null);
                    }
                  }}
                  type="single"
                  className="ch-flex ch-items-center ch-gap-2 ch-mt-1.5"
                >
                  <ReactToggle.Item
                    className="ch-text-sm ch-bg-fg ch-transition-all data-[state=on]:ch-bg-primary ch-px-2 ch-py-1 ch-rounded-md"
                    value="yes"
                  >
                    Yes
                  </ReactToggle.Item>
                  <ReactToggle.Item
                    className="ch-text-sm ch-bg-fg ch-transition-all data-[state=on]:ch-bg-primary ch-px-2 ch-py-1 ch-rounded-md"
                    value="no"
                  >
                    No
                  </ReactToggle.Item>
                </ReactToggle.Root>
              </div>
            )}
            {bot && sources && isComplete && (
              <>
                <div className="ch-w-full ch-block ch-h-px ch-bg-bg_light ch-my-2" />
                <div className="sources ch-w-full fade-in-bottom">
                  <div className="ch-w-full">
                    <span className="ch-block ch-text-sm">
                      Verified Sources:
                    </span>
                    <div className="ch-flex ch-items-center ch-justify-start ch-gap-2 ch-flex-wrap ch-mt-1">
                      {sources?.map((source, i) => (
                        <Source href={source.source_url} key={i}>
                          {i + 1}.{source.source_text}
                        </Source>
                      ))}
                    </div>
                  </div>
                </div>
              </>
            )}

            {!firstMsg && bot && (
              <div
                className="ch-flex ch-items-center ch-justify-end ch-w-full"
                style={{ visibility: "hidden" }}
              >
                <div className="ch-w-fit ch-flex ch-items-center ch-gap-0.5 ">
                  <button
                    onClick={copy}
                    className="ch-text-sm ch-p-1 ch-rounded-md ch-text-accent hover:ch-bg-bg_light"
                  >
                    {!isCopid ? (
                      <BsFillClipboardFill />
                    ) : (
                      <BsFillClipboard2CheckFill />
                    )}
                  </button>
                  <ReactToggle.Root
                    onValueChange={(v) => {
                      if (v) {
                        rateMessage(v === "yes" ? true : false);
                      } else {
                        rateMessage(null);
                      }
                    }}
                    type="single"
                    className="ch-flex ch-items-center ch-gap-0.5"
                  >
                    <ReactToggle.Item
                      className="data-[state=on]:ch-bg-primary data-[state=on]:ch-opacity-100 data-[state=on]:hover:ch-opacity-100 ch-text-sm ch-p-1 ch-rounded-md ch-text-accent ch-opacity-40 hover:ch-opacity-60 data-[state=off]:hover:ch-bg-bg_light"
                      value="yes"
                    >
                      <AiFillDislike />
                    </ReactToggle.Item>
                    <ReactToggle.Item
                      className="data-[state=on]:ch-bg-primary data-[state=on]:ch-opacity-100 data-[state=on]:hover:ch-opacity-100 ch-text-sm ch-p-1 ch-rounded-md ch-text-accent ch-opacity-40 hover:ch-opacity-60 data-[state=off]:hover:ch-bg-bg_light"
                      value="no"
                    >
                      <AiFillLike />
                    </ReactToggle.Item>
                  </ReactToggle.Root>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
