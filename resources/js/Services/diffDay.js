import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

export function diffForHuman(date) {
    return dayjs(date).fromNow();
}
