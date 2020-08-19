/*!
 * FileInput Chinese Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author kangqf <kangqingfei@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['zh'] = {
        fileSingle: '图片',
        filePlural: '个图片',
        browseLabel: '选择',
        removeLabel: '移除',
        removeTitle: '清除选中图片',
        cancelLabel: '取消',
        cancelTitle: '取消进行中的上传',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: '上传',
        uploadTitle: '上传选中图片',
        msgNo: '没有',
        msgNoFilesSelected: '未选择图片',
        msgPaused: 'Paused',
        msgCancelled: '取消',
        msgPlaceholder: '选择需要上传的图片,图片大小不能超过300kb',
        msgZoomModalHeading: '详细预览',
        msgFileRequired: '必须选择一个图片上传.',
        msgSizeTooSmall: '图片 "{name}" (<b>{size} KB</b>) 必须大于限定大小 <b>{minSize} KB</b>.',
        msgSizeTooLarge: '图片 "{name}" (<b>{size} KB</b>) 超过了允许大小 <b>{maxSize} KB</b>.',
        msgFilesTooLess: '你必须选择最少 <b>{n}</b> {files} 来上传. ',
        msgFilesTooMany: '选择的上传图片个数 <b>({n})</b> 超出最大图片的限制个数 <b>{m}</b>.',
        msgFileNotFound: '图片 "{name}" 未找到!',
        msgFileSecured: '安全限制，为了防止读取图片 "{name}".',
        msgFileNotReadable: '图片 "{name}" 不可读.',
        msgFilePreviewAborted: '取消 "{name}" 的预览.',
        msgFilePreviewError: '读取 "{name}" 时出现了一个错误.',
        msgInvalidFileName: '图片名 "{name}" 包含非法字符.',
        msgInvalidFileType: '不正确的类型 "{name}". 只支持 "{types}" 类型的图片.',
        msgInvalidFileExtension: '不正确的图片扩展名 "{name}". 只支持 "{extensions}" 的图片扩展名.',
        msgFileTypes: {
            'image': 'image',
            'html': 'HTML',
            'text': 'text',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: '该图片上传被中止',
        msgUploadThreshold: '处理中...',
        msgUploadBegin: '正在初始化...',
        msgUploadEnd: '完成',
        msgUploadResume: 'Resuming upload...',
        msgUploadEmpty: '无效的图片上传.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: '上传出错',
        msgValidationError: '验证错误',
        msgLoading: '加载第 {index} 图片 共 {files} &hellip;',
        msgProgress: '加载第 {index} 图片 共 {files} - {name} - {percent}% 完成.',
        msgSelected: '{n} {files} 选中',
        msgFoldersNotAllowed: '只支持拖拽图片! 跳过 {n} 拖拽的图片夹.',
        msgImageWidthSmall: '图像图片的"{name}"的宽度必须是至少{size}像素.',
        msgImageHeightSmall: '图像图片的"{name}"的高度必须至少为{size}像素.',
        msgImageWidthLarge: '图像图片"{name}"的宽度不能超过{size}像素.',
        msgImageHeightLarge: '图像图片"{name}"的高度不能超过{size}像素.',
        msgImageResizeError: '无法获取的图像尺寸调整。',
        msgImageResizeException: '调整图像大小时发生错误。<pre>{errors}</pre>',
        msgAjaxError: '{operation} 发生错误. 请重试!',
        msgAjaxProgressError: '{operation} 失败',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: '删除图片',
            uploadThumb: '上传图片',
            uploadBatch: '批量上传',
            uploadExtra: '表单数据上传'
        },
        dropZoneTitle: '请选择上传图片',
        dropZoneClickTitle: '<br>(或点击{files}按钮选择图片)',
        fileActionSettings: {
            removeTitle: '删除图片',
            uploadTitle: '上传图片',
            downloadTitle: '下载图片',
            uploadRetryTitle: '重试',
            zoomTitle: '查看详情',
            dragTitle: '移动 / 重置',
            indicatorNewTitle: '没有上传',
            indicatorSuccessTitle: '上传',
            indicatorErrorTitle: '上传错误',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  '上传 ...'
        },
        previewZoomButtonTitles: {
            prev: '预览上一个图片',
            next: '预览下一个图片',
            toggleheader: '缩放',
            fullscreen: '全屏',
            borderless: '无边界模式',
            close: '关闭当前预览'
        }
    };
})(window.jQuery);
