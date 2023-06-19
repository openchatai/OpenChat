import { useEffect, useRef, useState } from "react";
import * as Dialog from "@radix-ui/react-dialog";
import ModalTrigger from "./components/ModalTrigger";
import Message from "./components/Message";
import { instance } from "./utils/axios_instance";
import TextareaAutosize from "react-autosize-textarea";
import { AiOutlineArrowUp } from "react-icons/ai";
import { useHotkeys } from "react-hotkeys-hook";
import { Grid } from "react-loader-spinner";
import { GrRotateLeft } from "react-icons/gr";
import { useBoolean } from "./hooks/useBoolean";

type Message = {
  timestamp: Date | number;
  content: string;
  bot?: boolean;
  firstMsg?: boolean;
  messageId?: string;
  sources?: Array<{ source_url: string; source_text: string }> | [];
};
type SuggestionType = { text: string; url: string };
export default function App({
  initialFirstMessage,
  token,
  initiatorId,
}: {
  initialFirstMessage: string;
  token?: string;
  initiatorId?: string;
}) {
  const {
    value: isOpen,
    setTrue: open,
    // setFalse: close,
    toggle: toggle,
    setValue: setOpen,
  } = useBoolean(false);
  const [userInput, setUserInput] = useState("");
  const [messages, setMessages] = useState<Message[]>([]);
  const [loading, setLoading] = useState(false);

  const [suggestions, setSuggestions] = useState<Array<SuggestionType> | []>(
    []
  );
  // token stuff
  if (token) {
    instance.defaults.headers["X-Bot-Token"] = token;
  } else {
    console.warn("OpenChat bot token is not provided");
  }
  useEffect(() => {
    if (initiatorId) {
      const initiatorElement = document.getElementById(initiatorId);
      initiatorElement?.addEventListener("click", open);
      return () => initiatorElement?.removeEventListener("click", open);
    }
  }, []);
  useHotkeys(["/"], toggle);
  // get suggestions endpoint request
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
  const messagesContainerRef = useRef<HTMLDivElement>(null);
  function scrollDown() {
    if (messagesContainerRef.current) {
      messagesContainerRef.current.scrollTop =
        messagesContainerRef.current.scrollHeight;
    }
  }
  useEffect(() => {
    scrollDown();
  }, [messages]);

  useEffect(() => {
    if (loading) {
      scrollDown();
    }
  }, [loading]);
  const handleSendMessage = async (message: string) => {
    if (message.trim() === "") return;
    const history = messages
      .filter((value) => !value.bot)
      .map((message) => message.content);

    setLoading(true);
    // Create a new user message object
    const newUserMessage: Message = {
      timestamp: new Date(),
      content: message,
    };

    // Add the user message to the messages array
    setMessages((prevMessages) => [...prevMessages, newUserMessage]);

    try {
      // Make an API call to send the user message and get the bot response
      const response = await instance.post(
        "/chat/search",
        { message: message, history: history }
      );

      if ((await response.status) === 200) {
        // Parse the bot response from the API
        const botResponse = await response.data;

        // Create a new bot message object
        const newBotMessage: Message = {
          timestamp: new Date(),
          content: botResponse.ai_response,
          bot: true,
          messageId: botResponse.message_id,
          sources: botResponse.sources,
        };
        console.log(botResponse.sources);

        setMessages((prevMessages) => [...prevMessages, newBotMessage]);
      } else {
        console.error("Failed to send user message");
      }
    } catch (error) {
      console.error("Error sending user message:", error);
    }
    setLoading(false);
  };

  return (
    <Dialog.Root open={isOpen} onOpenChange={(open) => setOpen(open)}>
      {!initiatorId && <ModalTrigger onClick={open} isOpen={isOpen} />}
      <Dialog.Portal className="!z-[10000000]">
        <Dialog.Overlay className="ch-bg-black/50 !ch-z-[10000000] ch-font-manrope data-[state=open]:ch-animate-overlayShow ch-fixed ch-inset-0 ch-flex ch-items-start ch-justify-center ch-pt-10 pb-4 px-5">
          <Dialog.Content className="!ch-z-[100000000] data-[state=open]:ch-animate-contentShow ch-focus:outline-none ch-max-w-[95%] ch-max-h-full sm:ch-max-w-screen-md ch-w-full">
            <div className="ch-w-full ch-relative ch-rounded-lg ch-text-accent ch-p-4 ch-overflow-auto ch-flex ch-items-center ch-flex-col ch-max-h-[calc(100dvh-5rem)] ch-bg-bg ch-min-h-fit">
              {messages.length > 1 && (
                <button
                  onClick={() => {
                    setMessages([]);
                  }}
                  className="reset ch-absolute ch-gap-1.5 !ch-z-[10001] ch-right-3 ch-top-2 ch-px-2 ch-py-1 ch-flex ch-items-center ch-self-end  ch-whitespace-nowrap ch-rounded-lg  ch-text-xs sm:ch-text-sm ch-bg-opacity-100 ch-opacity-100 ch-text-white ch-bg-fg hover:ch-bg-opacity-75 hover:ch-cursor-pointer "
                >
                  <GrRotateLeft />
                  <span>reset</span>
                </button>
              )}

              <div
                ref={messagesContainerRef}
                className="ch-w-full ch-max-w-full ch-overflow-y-auto ch-scrollbar-thin ch-scrollbar-bg_light ch-scroll-smooth ch-scrollbar-corner-transparent"
              >
                <div
                  tabIndex={0}
                  className="ch-flex ch-items-start ch-flex-col ch-pb-5 ch-gap-1 ch-divide-y-[0.5px] ch-divide-bg_light ch-max-w-full"
                >
                  <Message bot firstMsg>
                    {initialFirstMessage}
                  </Message>

                  {messages?.map(({ content, sources, ...msg }, index) => (
                    <Message key={index} sources={sources} {...msg}>
                      {content}
                    </Message>
                  ))}
                  {loading && (
                    <div className="ch-flex ch-w-full ch-py-2 ch-flex-col ch-items-center ch-justify-center ch-gap-2">
                      <Grid
                        radius={5}
                        height="30"
                        width="30"
                        color="var(--color-primary)"
                      />
                      <p className="ch-text-xs ch-font-normal">
                        Scanning through the available resources. Just a moment, please!
                      </p>
                    </div>
                  )}
                </div>
              </div>
              <div className="ch-w-full">
                <div className="ch-w-full ch-p-2 ch-flex ch-flex-col ch-items-start ch-gap-2">
                  <div className="ch-flex ch-items-center ch-gap-1 ch-justify-start ch-flex-wrap">
                    {suggestions.map((s, i) => (
                      <button
                        onClick={() => {
                          handleSendMessage(s.text);
                        }}
                        key={i}
                        className="ch-text-sm ch-font-medium ch-px-2 ch-py-1 ch-bg-fg ch-rounded-md ch-text-accent text-focus-in"
                        style={{ animationDelay: `0.${i}s` }}
                      >
                        {s.text}
                      </button>
                    ))}
                  </div>
                  <div className="ch-bg-bg_light ch-rounded-xl ch-p-1.5 ch-w-full">
                    <div className="ch-flex ch-gap-1 w-full ch-items-center ch-flex-row">
                      <div className="ch-flex-1 p-1">
                        <TextareaAutosize
                          autoFocus
                          onKeyDown={(event) => {
                            if (event.key === "Enter" && !loading) {
                              event.preventDefault();
                              handleSendMessage(userInput);
                              setUserInput("");
                            }
                          }}
                          onChange={(ev) =>
                            setUserInput(ev.currentTarget.value)
                          }
                          value={userInput}
                          maxRows={10}
                          className="ch-w-full focus:ch-outline-none ch-leading-7 placeholder:ch-leading-loose ch-text-accent placeholder:ch-text-sm ch-bg-transparent ch-text-lg ch-font-normal ch-p-1 ch-align-middle ch-min-h-full ch-outline-none"
                          type="text"
                          placeholder="ask any question?"
                        />
                      </div>
                      <button
                        onClick={() => {
                          handleSendMessage(userInput);
                          setUserInput("");
                        }}
                        disabled={loading}
                        className="ch-z-10 ch-text-sm ch-font-normal sm:ch-text-base ch-m-0 disabled:ch-bg-bg ch-p-0 ch-text-accent ch-bg-primary ch-gap-1 ch-items-center ch-rounded-lg ch-flex ch-py-px ch-px-[7px] disabled:ch-text-opacity-50 disabled:ch-cursor-none ch-transition-all"
                      >
                        {loading ? (
                          <Grid
                            radius={5}
                            height="20"
                            width="20"
                            color="var(--color-accent)"
                          />
                        ) : (
                          <AiOutlineArrowUp />
                        )}
                        <span>{loading ? "Generating" : "Ask"}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div>
                <div className="powered-by">
                  Powered by <a className="ch-text-accent" href="https://openchat.so">OpenChat</a>
                </div>
            </div>
          </Dialog.Content>
        </Dialog.Overlay>
      </Dialog.Portal>
    </Dialog.Root>
  );
}
