import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);
export function useFormatDate() {

    function formatDate(date) {
        return dayjs(date).fromNow();
    }

    return {
        formatDate
    };
}
